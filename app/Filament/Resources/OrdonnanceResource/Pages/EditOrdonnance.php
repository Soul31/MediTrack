<?php

namespace App\Filament\Resources\OrdonnanceResource\Pages;

use App\Filament\Resources\OrdonnanceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrdonnance extends EditRecord
{
    protected static string $resource = OrdonnanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
