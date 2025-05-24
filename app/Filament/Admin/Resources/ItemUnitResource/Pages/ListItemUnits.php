<?php

namespace App\Filament\Admin\Resources\ItemUnitResource\Pages;

use App\Filament\Admin\Resources\ItemUnitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListItemUnits extends ListRecords
{
    protected static string $resource = ItemUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}