<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VenteResource\Pages;
use App\Filament\Resources\VenteResource\RelationManagers;
use App\Models\Vente;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Medicament;

class VenteResource extends Resource
{
    protected static ?string $model = Vente::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('statut')
                    ->label('Statut')
                    ->options([
                        'en attente' => 'En attente',
                        'valide' => 'Validé',
                        'livre' => 'Livré',
                        'refus' => 'Refusé',
                    ])
                    ->required()
                    ->native(false) // Makes the select more user-friendly
                    ->default('en attente'), // Sets default value
                Forms\Components\TextInput::make('total')
                    ->required()
                    ->numeric()
                    ->readOnly()
                    ->default(0),
                Forms\Components\Repeater::make('lignes')
                    ->relationship()
                    ->schema([
                        Forms\Components\Select::make('medicament_id')
                            ->relationship('medicament', 'nom')
                            ->required(),

                        Forms\Components\TextInput::make('quantite')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('montant')
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
            ])->live();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
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
            'index' => Pages\ListVentes::route('/'),
            'create' => Pages\CreateVente::route('/create'),
            'edit' => Pages\EditVente::route('/{record}/edit'),
        ];
    }
}
