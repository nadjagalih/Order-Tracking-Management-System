<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class BrandWidget extends Widget
{
    protected static string $view = 'filament.widgets.brand-widget';
    
    protected int | string | array $columnSpan = 'full';
    
    protected static ?int $sort = -1;
}
