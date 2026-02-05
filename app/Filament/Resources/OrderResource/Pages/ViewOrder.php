<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
            Actions\Action::make('view_tracking')
                ->label('Lihat Halaman Tracking')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->color('info')
                ->url(fn ($record) => url('/track/' . $record->order_code))
                ->openUrlInNewTab(),
        ];
    }
    
    public function getRelationManagers(): array
    {
        return [
            \App\Filament\Resources\OrderResource\RelationManagers\ProgressRelationManager::class,
        ];
    }
}
