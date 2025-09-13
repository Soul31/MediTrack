<?php

namespace App\Filament\Widgets;

use App\Models\Commande;
use App\Models\Vente;
use App\Models\Ordonnance;
use Flowframe\Trend\Trend;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\ChartWidget;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;

class RevenueOverview extends ChartWidget
{
    protected static ?string $heading = 'Revenue Analysis';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $years = range(
            min(
                Commande::oldest('created_at')->value('created_at')?->year ?? now()->year,
                Vente::oldest('created_at')->value('created_at')?->year ?? now()->year,
                Ordonnance::oldest('created_at')->value('created_at')?->year ?? now()->year,
                2020,
            ),
            now()->year
        );

        $yearlyRevenue = [];
        foreach ($years as $year) {
            $yearlyRevenue[] =
                Commande::whereYear('created_at', $year)->sum('total') +
                Vente::whereYear('created_at', $year)->sum('total') +
                Ordonnance::whereYear('created_at', $year)->sum('total');
        }

        return [
            'datasets' => [
                [
                    'label' => "Revenue Annuel",
                    'data' => $yearlyRevenue,
                    'backgroundColor' => '#3b82f6',
                    'borderColor' => '#3b82f6',
                    'fill' => false,
                ],
            ],
            'labels' => $years,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getStats(): array
    {
        $totalRevenue =
            Commande::sum('total') +
            Vente::sum('total') +
            Ordonnance::sum('total');

        $currentYearRevenue =
            Commande::whereYear('created_at', now()->year)->sum('total') +
            Vente::whereYear('created_at', now()->year)->sum('total') +
            Ordonnance::whereYear('created_at', now()->year)->sum('total');

        $lastYearRevenue =
            Commande::whereYear('created_at', now()->subYear()->year)->sum('total') +
            Vente::whereYear('created_at', now()->subYear()->year)->sum('total') +
            Ordonnance::whereYear('created_at', now()->subYear()->year)->sum('total');

        $growth = $lastYearRevenue ?
            round(($currentYearRevenue - $lastYearRevenue) / $lastYearRevenue * 100, 2) : 0;

        return [
            Stat::make('Revenue Total', number_format($totalRevenue, 2) . ' DH')
                ->description('Tout le temps')
                ->color('primary'),

            Stat::make('Revenue Année Courante', number_format($currentYearRevenue, 2) . ' DH')
                ->description($growth >= 0 ? "↑ {$growth}%" : "↓ {$growth}%")
                ->color($growth >= 0 ? 'success' : 'danger'),

            Stat::make('Moyenne Mensuelle', number_format($currentYearRevenue / now()->month, 2) . ' DH')
                ->description('Cette année')
                ->color('info'),
        ];
    }

}
