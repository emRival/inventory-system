<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class ReturnedChart extends ChartWidget
{
    protected static ?string $heading = 'Returned Items Chart';
    protected static ?string $description = 'A chart showing the number of returned items over time.';
    protected static ?int $sort = 3; // Position in the dashboard
    public ?string $filter = 'month'; // Default filter

    protected function getData(): array
    {
        $startDate = null;
        $endDate = null;

        // Tentukan rentang tanggal berdasarkan filter yang dipilih
        switch ($this->filter) {
            case 'today':
                $startDate = now()->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case 'week':
                $startDate = now()->startOfWeek();
                $endDate = now()->endOfWeek();
                break;
            case 'month':
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                break;
            case 'year':
                $startDate = now()->startOfYear();
                $endDate = now()->endOfYear();
                break;
        }

        $rawData = DB::table('item_units')
            ->where('status', 'returned') // Filter only returned items
            ->selectRaw("DATE(created_at) as day, COUNT(*) as total")
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('day')
            ->get();

        $labels = [];
        $totals = [];

        foreach ($rawData as $row) {
            $labels[] = Carbon::parse($row->day)->format('d M'); // ex: 01 May, 02 May
            $totals[] = $row->total;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Returned Items',
                    'data' => $totals,
                    'borderColor' => 'rgba(255, 99, 132, 1)', // Red for returned items
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)', // Light red for transparency
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'week' => 'Last week',
            'month' => 'Last month',
            'year' => 'This year',
        ];
    }
}