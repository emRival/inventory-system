<?php

namespace App\Filament\Admin\Resources\DistributionResource\Pages;

use App\Filament\Admin\Resources\DistributionResource;
use App\Models\Distribution;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListDistributions extends ListRecords
{
    protected static string $resource = DistributionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'All' => Tab::make(),
            'This Week' => Tab::make()
                ->modifyQueryUsing(fn($query) => $query->where('created_at', '>=', now()->startOfWeek()))
                ->badge(fn() => Distribution::where('created_at', '>=', now()->startOfWeek())->count()),
            'This Month' => Tab::make()
                ->modifyQueryUsing(fn($query) => $query->where('created_at', '>=', now()->startOfMonth()))
                ->badge(fn() => Distribution::where('created_at', '>=', now()->startOfMonth())->count()),
            'This Year' => Tab::make()
                ->modifyQueryUsing(fn($query) => $query->where('created_at', '>=', now()->startOfYear()))
                ->badge(fn() => Distribution::where('created_at', '>=', now()->startOfYear())->count()),

        ];
    }
}