<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommandeResource\Pages;
use App\Filament\Resources\CommandeResource\RelationManagers;
use App\Models\Commande;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\{
    Patient,
    Medicament,
};
use Filament\Forms\Components\{
    TextInput,
    Select,
    Repeater,
};
use Filement\Forms\{
    Set,
};

class CommandeResource extends Resource
{
    protected static ?string $model = Commande::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('statut')
                    ->label('Statut')
                    ->options([
                        'en attente' => 'En attente',
                        'valide' => 'Validé',
                        'livre' => 'Livré',
                        'refus' => 'Refusé',
                    ])
                    ->required()
                    ->native(false) // Makes the select more user-friendly
                    ->searchable() // Optional if you want search functionality
                    ->default('en attente'), // Sets default value
                TextInput::make('total')
                    ->required()
                    ->numeric()
                    ->readOnly()
                    ->live()
                    ->default(0),
                Select::make('patient_id')
                    ->label('Patient')
                    ->relationship(
                        name: 'patient',
                        titleAttribute: 'id', // We'll override the display
                        modifyQueryUsing: fn (Builder $query) => $query->with('user')
                    )
                    ->getOptionLabelFromRecordUsing(function (Patient $patient) {
                        return $patient->user
                            ? "{$patient->user->nom} {$patient->user->prenom}"
                            : 'No User Assigned';
                    })
                    ->searchable()
                    ->getSearchResultsUsing(function (string $search) {
                        return Patient::query()
                            ->with('user')
                            ->whereHas('user', function($query) use ($search) {
                                $query->where('nom', 'like', "%{$search}%")
                                    ->orWhere('prenom', 'like', "%{$search}%");
                            })
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(function (Patient $patient) {
                                return [
                                    $patient->id => $patient->user
                                        ? "{$patient->user->nom} {$patient->user->prenom}"
                                        : 'No User'
                                ];
                            });
                    })
                    ->required()
                    ->native(false)
                    ->preload()
                    // Optional: Add a loading message
                    ->loadingMessage('Loading patients...')
                    ->noSearchResultsMessage('No patients found.')
                    ->hint('Search by first or last name'),
                Repeater::make('lignes')
                ->relationship()
                ->schema([
                    Select::make('medicament_id')
                        ->relationship(
                            name: 'medicament',
                            titleAttribute: 'nom',
                        )
                        ->required()
                        ->live(),

                    TextInput::make('quantite')
                        ->numeric()
                        ->required(),
                    TextInput::make('montant')
                        ->numeric()
                        ->readOnly()
                        ->dehydrated()
                        ->default(0),
                ])
                ->columns(3)
                ->label('Order Items')
                ->addActionLabel('Add Item')
                ->minItems(1)
                ->collapsible()
                ->itemLabel(fn (array $state): ?string =>
                    isset($state['medicament_id'])
                        ? Medicament::find($state['medicament_id'])?->nom
                        : null
                ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('dateCreation')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('statut')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('patient.user.nom')
                    ->label('Nom du Patient'),
                Tables\Columns\TextColumn::make('patient.user.prenom')
                    ->label('Prenom du Patient'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCommandes::route('/'),
            'create' => Pages\CreateCommande::route('/create'),
            'edit' => Pages\EditCommande::route('/{record}/edit'),
        ];
    }
}
