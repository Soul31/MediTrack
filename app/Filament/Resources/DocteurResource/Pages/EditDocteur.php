<?php

namespace App\Filament\Resources\DocteurResource\Pages;

use App\Filament\Resources\DocteurResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDocteur extends EditRecord
{
    protected static string $resource = DocteurResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
