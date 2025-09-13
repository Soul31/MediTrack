<?php

namespace App\Filament\Widgets;

use App\Models\Commande;
use App\Models\Vente;
use App\Models\Ordonnance;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Checkbox;

class CommandesStats extends ChartWidget
{
    protected static ?string $heading = 'Statistiques par Mois';
    protected static ?int $sort = 2;

    public ?string $filter = null;
    public array $activeModels = ['commande', 'vente', 'ordonnance'];

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

        $datasets = [];
        $colors = [
            'commande' => ['bg' => '#4f46e5', 'border' => '#4f46e5'],
            'vente' => ['bg' => '#10b981', 'border' => '#10b981'],
            'ordonnance' => ['bg' => '#f59e0b', 'border' => '#f59e0b'],
        ];

        // Commandes data
        if ($this->activeModels['commande'] ?? true) {
            $commandeData = Trend::model(Commande::class)
                ->between($startDate, $endDate)
                ->perMonth()
                ->count();

            $datasets[] = [
                'label' => "Commandes $selectedYear",
                'data' => $commandeData->map(fn (TrendValue $value) => $value->aggregate),
                'backgroundColor' => $colors['commande']['bg'],
                'borderColor' => $colors['commande']['border'],
            ];
        }

        // Ventes data
        if ($this->activeModels['vente'] ?? true) {
            $venteData = Trend::model(Vente::class)
                ->between($startDate, $endDate)
                ->perMonth()
                ->count();

            $datasets[] = [
                'label' => "Ventes $selectedYear",
                'data' => $venteData->map(fn (TrendValue $value) => $value->aggregate),
                'backgroundColor' => $colors['vente']['bg'],
                'borderColor' => $colors['vente']['border'],
            ];
        }

        // Ordonnances data
        if ($this->activeModels['ordonnance'] ?? true) {
            $ordonnanceData = Trend::model(Ordonnance::class)
                ->between($startDate, $endDate)
                ->perMonth()
                ->count();

            $datasets[] = [
                'label' => "Ordonnances $selectedYear",
                'data' => $ordonnanceData->map(fn (TrendValue $value) => $value->aggregate),
                'backgroundColor' => $colors['ordonnance']['bg'],
                'borderColor' => $colors['ordonnance']['border'],
            ];
        }

        // Get month labels from any dataset (they all have the same months)
        $labels = isset($commandeData)
            ? $commandeData->map(fn (TrendValue $value) => $value->date)
            : (isset($venteData)
                ? $venteData->map(fn (TrendValue $value) => $value->date)
                : $ordonnanceData->map(fn (TrendValue $value) => $value->date));

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
