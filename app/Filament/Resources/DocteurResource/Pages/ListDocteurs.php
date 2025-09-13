<?php

namespace App\Filament\Resources\DocteurResource\Pages;

use App\Filament\Resources\DocteurResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDocteurs extends ListRecords
{
    protected static string $resource = DocteurResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
