<?php

namespace App\Filament\Widgets;

use App\Models\Commande;
use App\Models\Vente;
use App\Models\Ordonnance;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;
use Filament\Forms\Components\Select;

class RevenueStats extends ChartWidget
{
    protected static ?string $heading = 'Revenue par Mois';
    protected static ?int $sort = 3;

    public ?string $filter = null;

    protected function getFilters(): array
    {
        // Get the earliest year from all revenue-generating models
        $firstYear = min(
            Commande::query()->orderBy('created_at')->value('created_at')?->year ?? now()->year,
            Vente::query()->orderBy('created_at')->value('created_at')?->year ?? now()->year,
            Ordonnance::query()->orderBy('created_at')->value('created_at')?->year ?? now()->year,
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

        // Get revenue data from each model
        $commandeRevenue = Trend::model(Commande::class)
            ->between($startDate, $endDate)
            ->perMonth()
            ->sum('total');

        $venteRevenue = Trend::model(Vente::class)
            ->between($startDate, $endDate)
            ->perMonth()
            ->sum('total');

        $ordonnanceRevenue = Trend::model(Ordonnance::class)
            ->between($startDate, $endDate)
            ->perMonth()
            ->sum('total');

        // Combine all revenue streams
        $totalRevenue = [];
        $labels = $commandeRevenue->map(fn (TrendValue $value) => $value->date);

        foreach ($labels as $index => $date) {
            $totalRevenue[] =
                ($commandeRevenue[$index]->aggregate ?? 0) +
                ($venteRevenue[$index]->aggregate ?? 0) +
                ($ordonnanceRevenue[$index]->aggregate ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => "Revenue Total ($selectedYear)",
                    'data' => $totalRevenue,
                    'backgroundColor' => '#10b981',
                    'borderColor' => '#10b981',
                    // 'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
