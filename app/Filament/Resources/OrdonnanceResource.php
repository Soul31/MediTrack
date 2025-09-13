<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrdonnanceResource\Pages;
use App\Filament\Resources\OrdonnanceResource\RelationManagers;
use App\Models\{
    Ordonnance,
    Medicament,
    Patient,
    Docteur,
};
use Filament\Forms\Components\{
    TextInput,
    Select,
    Repeater,
};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrdonnanceResource extends Resource
{
    protected static ?string $model = Ordonnance::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
                Select::make('docteur_id')
                    ->label('Docteur')
                    ->relationship(
                        name: 'docteur',
                        titleAttribute: 'id', // We'll override the display
                        modifyQueryUsing: fn (Builder $query) => $query->with('user')
                    )
                    ->getOptionLabelFromRecordUsing(function (Docteur $docteur) {
                        return $docteur->user
                            ? "{$docteur->user->nom} {$docteur->user->prenom}"
                            : 'No User Assigned';
                    })
                    ->searchable()
                    ->getSearchResultsUsing(function (string $search) {
                        return Docteur::query()
                            ->with('user')
                            ->whereHas('user', function($query) use ($search) {
                                $query->where('nom', 'like', "%{$search}%")
                                    ->orWhere('prenom', 'like', "%{$search}%");
                            })
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(function (Docteur $docteur) {
                                return [
                                    $docteur->id => $docteur->user
                                        ? "{$docteur->user->nom} {$docteur->user->prenom}"
                                        : 'No User'
                                ];
                            });
                    })
                    ->required()
                    ->native(false)
                    ->preload()
                    // Optional: Add a loading message
                    ->loadingMessage('Loading docteurs...')
                    ->noSearchResultsMessage('No docteurs found.')
                    ->hint('Search by first or last name'),
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
                    ->readOnly()
                    ->numeric()
                    ->default(0),
                Repeater::make('lignes')
                    ->relationship()
                    ->schema([
                        Select::make('medicament_id')
                            ->relationship('medicament', 'nom')
                            ->required(),

                        TextInput::make('quantite')
                            ->numeric()
                            ->required(),
                    ])
                    ->columns(2)
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
                Tables\Columns\TextColumn::make('patient.user.nom')
                    ->label('Nom du Patient')->sortable(),
                Tables\Columns\TextColumn::make('patient.user.prenom')
                    ->label('Prenom du Patient')->sortable(),
                Tables\Columns\TextColumn::make('docteur.user.nom')
                    ->label('Nom du Docteur')->sortable(),
                Tables\Columns\TextColumn::make('docteur.user.prenom')
                    ->label('Prenom du Docteur')->sortable(),
                Tables\Columns\TextColumn::make('dateCreation')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('statut')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListOrdonnances::route('/'),
            'create' => Pages\CreateOrdonnance::route('/create'),
            'edit' => Pages\EditOrdonnance::route('/{record}/edit'),
        ];
    }
}
