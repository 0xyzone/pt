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

    // In your Widget class (e.g., ApiEndpointsWidget.php)
    public function getApiRoutes(): array
    {
        return collect(\Illuminate\Support\Facades\Route::getRoutes())
            ->filter(fn($route) => str_starts_with($route->uri, 'api/'))
            ->map(function ($route) {
                // Replace dynamic parameters like {user_id} with the actual value
                $uri = str_replace('{user_id}', $this->userId, $route->uri);
                return url($uri);
            })
            ->values()
            ->toArray();
    }
    protected string $view = 'filament.maidan.widgets.api-link';
}
