<?php

namespace App\Filament\Widgets;

use App\Models\Patient;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;
use Filament\Forms\Components\Select;

class PatientGrowthChart extends ChartWidget
{
    protected static ?string $heading = 'Évolution des Patients';
    protected static ?int $sort = 4;

    public bool $showCumulative = true;

    protected function getFilters(): array
    {
        // Get the earliest year from all revenue-generating models
        $firstYear = min(
            Patient::query()->orderBy('created_at')->value('created_at')?->year ?? now()->year,
            2020
        );

        $currentYear = now()->year;
        $yearOptions = [];

        for ($year = $currentYear; $year >= $firstYear; $year--) {
            $yearOptions["$year"] = "$year";
        }

        return $yearOptions;
    }

    protected function getData(): array
    {
        $selectedYear = (int)($this->filter ?? now()->year);
        $startDate = now()->setYear($selectedYear)->startOfYear();
        $endDate = now()->setYear($selectedYear)->endOfYear();

        $monthlyData = Trend::model(Patient::class)
            ->between($startDate, $endDate)
            ->perMonth()
            ->count();

        $data = $monthlyData->map(fn (TrendValue $value) => $value->aggregate);

        if ($this->showCumulative) {
            $cumulative = 0;
            $data = $data->map(function ($value) use (&$cumulative) {
                $cumulative += $value;
                return $cumulative;
            });
        }

        return [
            'datasets' => [
                [
                    'label' => $this->showCumulative
                        ? "Patients (Cumulatif)"
                        : "Nouveaux Patients",
                    'data' => $data,
                    'backgroundColor' => $this->showCumulative
                        ? 'rgba(79, 70, 229, 0.2)'
                        : 'rgba(79, 70, 229, 0.5)',
                    'borderColor' => '#4f46e5',
                    'borderWidth' => 2,
                    'fill' => $this->showCumulative,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $monthlyData->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
