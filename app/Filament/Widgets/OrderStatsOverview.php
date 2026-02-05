<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Pesanan', Order::count())
                ->description('Semua pesanan')
                ->descriptionIcon('heroicon-o-shopping-bag')
                ->color('primary'),
            Stat::make('Sedang Dikerjakan', Order::where('status', 'in_progress')->count())
                ->description('Pesanan aktif')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),
            Stat::make('Selesai', Order::where('status', 'completed')->count())
                ->description('Pesanan selesai')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),
            Stat::make('Pending/Revisi', Order::whereIn('status', ['draft', 'review', 'revision', 'revision_1', 'revision_2'])->count())
                ->description('Menunggu proses')
                ->descriptionIcon('heroicon-o-exclamation-circle')
                ->color('info'),
        ];
    }
}
