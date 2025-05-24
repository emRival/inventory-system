<?php

namespace App\Filament\Admin\Resources\ReturnedItemResource\Pages;

use App\Filament\Admin\Resources\ReturnedItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReturnedItems extends ListRecords
{
    protected static string $resource = ReturnedItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}