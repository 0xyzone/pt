<?php

namespace App\Filament\Maidan\Widgets;

use Filament\Widgets\Widget;

class ApiLink extends Widget
{
    public $userId;

    public function mount(): void
    {
        $this->userId = auth()->id();
    }
    protected string $view = 'filament.maidan.widgets.api-link';
}
