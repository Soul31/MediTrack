<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PharmacienResource\Pages;
use App\Filament\Resources\PharmacienResource\RelationManagers;
use App\Models\Pharmacien;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PharmacienResource extends Resource
{
    protected static ?string $model = Pharmacien::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make('User Account')
                    ->relationship('user')
                    ->schema([
                        Forms\Components\TextInput::make('nom')
                            ->label('Last Name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('prenom')
                            ->label('First Name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->minLength(8)
                            ->dehydrated(fn ($state) => filled($state))
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->revealable()
                            ->confirmed()
                            ->required(fn (string $context) => $context === 'create')
                            ->visible(fn (string $context) => $context === 'create'),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Pharmacist Details')
                    ->schema([
                        Forms\Components\TextInput::make('licence')
                            ->label('Licence')
                            ->numeric()
                            ->maxLength(255),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.nom')
                    ->label('Nom'),
                Tables\Columns\TextColumn::make('user.prenom')
                    ->label('Prenom'),
                Tables\Columns\TextColumn::make('licence')
                    ->searchable(),
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
            'index' => Pages\ListPharmaciens::route('/'),
            'create' => Pages\CreatePharmacien::route('/create'),
            'edit' => Pages\EditPharmacien::route('/{record}/edit'),
        ];
    }
}
