<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Product;
use App\Models\Stock;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;


class LowStockTable extends BaseWidget
{
    protected static ?string $heading = 'Low Stock Products';

    protected static ?string $description = 'Products with low stock (<= 10) grouped by condition.';
    protected static ?int $sort = 4; // Position in the dashboard
    protected int|string|array $columnSpan = 'full'; // Lebar widget, bisa diubah sesuai kebutuhan

    public function table(Table $table): Table
    {
        // Gunakan DB::table untuk buat subquery yang aman
        $lowStockSubquery = DB::table('stocks')
            ->select('product_id', 'condition', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id', 'condition')
            ->having('total_quantity', '<=', 10);

        return $table
            ->query(
                // Bungkus subquery jadi builder dan join ke products
                Product::query()
                    ->joinSub($lowStockSubquery, 'low_stocks', function ($join) {
                        $join->on('products.id', '=', 'low_stocks.product_id');
                    })
                    ->select(
                        'products.*',
                        'products.unit as product_unit',
                        'low_stocks.condition',
                        'low_stocks.total_quantity'
                    )
            )
            ->columns([
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Product')
                    ->sortable(),

                Tables\Columns\TextColumn::make('condition')
                    ->label('Condition')
                    ->badge()
                    ->formatStateUsing(fn($state) => strtoupper($state))
                    ->color(fn($state) => match ($state) {
                        'baru' => 'success',
                        'bekas' => 'warning',
                    })
                    ->icon(fn($state) => match ($state) {
                        'baru' => 'heroicon-o-check-circle',
                        'bekas' => 'heroicon-o-clock',
                    }),

                Tables\Columns\TextColumn::make('total_quantity')
                    ->label('Remaining Quantity')
                    ->suffix(fn($record) => ' ' . $record->product_unit)

                    ->sortable(),
            ]);
    }
}