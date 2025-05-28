<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Distribution;
use App\Models\ItemUnit;
use App\Models\Product;
use App\Models\Stock;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{

    protected ?string $heading = 'Statistics Overview';

    protected ?string $description = 'A summary of key metrics and data insights.';
    protected function getStats(): array
    {
        return [
            Stat::make('Distributions', Distribution::count())
                ->description(Distribution::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count() . ' this week')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info'),
            Stat::make('Stock', Stock::select('product_id', 'condition')
                ->selectRaw('SUM(quantity) as total')
                ->groupBy('product_id', 'condition')
                ->havingRaw('SUM(quantity) >= 10')
                ->get()->count())
                ->description('Products with total stock >= 10')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->extraAttributes([
                    'title' => Stock::select('product_id', 'condition')
                        ->selectRaw('SUM(quantity) as total')
                        ->groupBy('product_id', 'condition')
                        ->havingRaw('SUM(quantity) > 10')
                        ->get()
                        ->map(function ($stock) {
                            $productName = Product::find($stock->product_id)->name ?? 'Unknown';
                            return "{$productName} ({$stock->condition})";
                        })
                        ->implode(', ')
                ])
                ->color('success'),
            Stat::make('Returned Items', ItemUnit::where('status', 'returned')->count())
                ->description('Items marked as returned')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('warning'),
        ];
    }
}