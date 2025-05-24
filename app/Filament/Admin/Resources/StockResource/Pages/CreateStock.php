<?php

namespace App\Filament\Admin\Resources\StockResource\Pages;

use App\Filament\Admin\Resources\StockResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateStock extends CreateRecord
{
    protected static string $resource = StockResource::class;
}
