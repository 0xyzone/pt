<?php

namespace App\Filament\Maidan\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class ObsControlWidget extends Widget
{
    protected static ?int $sort = -2; // Put it at the top
    
    protected int | string | array $columnSpan = 'full';

    protected string $view = 'filament.maidan.widgets.obs-control-widget';

    public function getLinks(): array
    {
        $userId = Auth::id();
        
        return [
            [
                'name' => 'Control Panel',
                'url' => route('screens.controlpanel', ['user_id' => $userId]),
                'icon' => 'heroicon-o-adjustments-horizontal',
                'description' => 'Live director console for managing OBS scenes and visibility.',
                'color' => 'danger',
                'is_main' => true,
            ],
            [
                'name' => 'Live Stats Control',
                'url' => route('screens.statscontrol', ['user_id' => $userId]),
                'icon' => 'heroicon-o-table-cells',
                'description' => 'Real-time match stats editor with live sync across all clients.',
                'color' => 'warning',
                'is_main' => true,
            ],
            [
                'name' => 'OBS Master Overlay',
                'url' => route('screens.obsmaster', ['user_id' => $userId]),
                'icon' => 'heroicon-o-tv',
                'description' => 'Main broadcast overlay for Post-Match & Standings.',
                'color' => 'primary',
            ],
            [
                'name' => 'Live Ranking HUD',
                'url' => route('screens.activematch', ['user_id' => $userId]),
                'icon' => 'heroicon-o-list-bullet',
                'description' => 'Real-time scrolling leaderboard for active matches.',
                'color' => 'warning',
            ],
            [
                'name' => 'Map Screen',
                'url' => route('screens.mapscreen', ['user_id' => $userId]),
                'icon' => 'heroicon-o-map',
                'description' => '1080x1080 tactical map view with team locations.',
                'color' => 'info',
            ],
            [
                'name' => 'Elimination HUD',
                'url' => route('screens.teamelimination', ['user_id' => $userId]),
                'icon' => 'heroicon-o-user-minus',
                'description' => 'Top-center notifications for squad eliminations.',
                'color' => 'danger',
            ],
            [
                'name' => 'Post-Match Stats',
                'url' => route('screens.postmatch', ['user_id' => $userId]),
                'icon' => 'heroicon-o-trophy',
                'description' => 'Detailed statistical summary of the last completed match.',
                'color' => 'success',
            ],
            [
                'name' => 'Overall Standings',
                'url' => route('screens.overallranking', ['user_id' => $userId]),
                'icon' => 'heroicon-o-chart-bar',
                'description' => 'Tournament-wide leaderboard across all matches.',
                'color' => 'primary',
            ],
            [
                'name' => 'Upcoming Matches',
                'url' => route('screens.upcomingmatches', ['user_id' => $userId]),
                'icon' => 'heroicon-o-calendar',
                'description' => 'Schedule and lineup for the next tournament matches.',
                'color' => 'gray',
            ],
            [
                'name' => 'Slot List',
                'url' => route('screens.slotlist', ['user_id' => $userId]),
                'icon' => 'heroicon-o-numbered-list',
                'description' => 'Public facing slot list view starting from 2.',
                'color' => 'indigo',
            ],
        ];
    }
}
