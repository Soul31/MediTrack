<?php

namespace App\Filament\Resources\PharmacienResource\Pages;

use App\Filament\Resources\PharmacienResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPharmacien extends EditRecord
{
    protected static string $resource = PharmacienResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
