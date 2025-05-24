<?php

namespace App\Filament\Exports;

use App\Models\ItemUnit;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ItemUnitExporter extends Exporter
{
    protected static ?string $model = ItemUnit::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('distribution_id')
                ->label('Distribution ID'),
            ExportColumn::make('distribution.product.category.name')
                ->label('Category Name'),
            ExportColumn::make('distribution.product.name')
                ->label('Product Name'),
            ExportColumn::make('distribution.sector.name')
                ->label('Sector Name'),
            ExportColumn::make('qr_code')
                ->label('QR Code'),
            ExportColumn::make('note')
                ->label('Note'),
            ExportColumn::make('status')
                ->label('Status'),
            ExportColumn::make('return_note')
                ->label('Return Note'),
            ExportColumn::make('return_date')
                ->label('Return Date'),
            ExportColumn::make('created_at')
                ->label('Distributed At'),


        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your item unit export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}