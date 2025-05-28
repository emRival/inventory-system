<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Distribution;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DistributionsChart extends ChartWidget
{
    protected static ?string $heading = 'Distributions Chart';
    protected static ?string $description = 'A chart showing the number of distributions over time.';

    public ?string $filter = 'month'; // Default filter

    protected static ?int $sort = 2; // Position in the dashboard

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

        $rawData = DB::table('distributions')
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
                    'label' => 'Distributions',
                    'data' => $totals,
                    'borderColor' => 'rgb(75, 192, 192)',
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
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