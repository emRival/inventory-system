<?php

namespace App\Filament\Admin\Resources\DistributionResource\Pages;

use App\Filament\Admin\Resources\DistributionResource;
use App\Livewire\QRProgressBar;
use App\Models\Stock;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDistribution extends EditRecord
{
    protected static string $resource = DistributionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
   
}