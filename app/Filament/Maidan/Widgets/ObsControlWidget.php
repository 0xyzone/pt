<?php

namespace App\Filament\Maidan\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class ObsControlWidget extends Widget
{
    protected static ?int $sort = -2; // Put it at the top
    
    protected int | string | array $columnSpan = 'full';
    protected string $view = 'filament.maidan.widgets.obs-control-widget';

    public static function canView(): bool
    {
        return \App\Services\SubscriptionService::hasActiveSubscription(Auth::user());
    }

    public function getLinks(): array
    {
        $userId = Auth::id();
        
        return [
            // CONSOLES & EDITORS
            [
                'name' => 'Control Panel',
                'url' => route('screens.controlpanel', ['user_id' => $userId]),
                'icon' => 'heroicon-o-adjustments-horizontal',
                'description' => 'Live director console for managing OBS scenes and visibility.',
                'color' => 'danger',
                'category' => 'control',
                'is_main' => true,
            ],
            [
                'name' => 'Live Stats Control',
                'url' => route('screens.statscontrol', ['user_id' => $userId]),
                'icon' => 'heroicon-o-table-cells',
                'description' => 'Real-time match stats editor with live sync across all clients.',
                'color' => 'warning',
                'category' => 'control',
                'is_main' => true,
            ],
            [
                'name' => 'OBS Master Overlay',
                'url' => route('screens.obsmaster', ['user_id' => $userId]),
                'icon' => 'heroicon-o-tv',
                'description' => 'Main broadcast master overlay with smooth scene transitions.',
                'color' => 'primary',
                'category' => 'control',
            ],

            // PRE-MATCH SETUP
            [
                'name' => 'Upcoming Matches',
                'url' => route('screens.upcomingmatches', ['user_id' => $userId]),
                'icon' => 'heroicon-o-calendar',
                'description' => 'L-shaped advertisement frame with upcoming match schedules.',
                'color' => 'gray',
                'category' => 'pre_match',
            ],
            [
                'name' => 'Casters Overlay',
                'url' => route('screens.castersscreen', ['user_id' => $userId]),
                'icon' => 'heroicon-o-microphone',
                'description' => 'Live casters feed display with robust VDO.Ninja framing.',
                'color' => 'info',
                'category' => 'pre_match',
            ],
            [
                'name' => 'Starting Soon',
                'url' => route('screens.startingsoon', ['user_id' => $userId]),
                'icon' => 'heroicon-o-clock',
                'description' => 'Professional pre-stream countdown timer and sponsor ads.',
                'color' => 'warning',
                'category' => 'pre_match',
            ],
            [
                'name' => 'Slot List',
                'url' => route('screens.slotlist', ['user_id' => $userId]),
                'icon' => 'heroicon-o-numbered-list',
                'description' => 'Public slot allocations for lobby teams starting from slot 2.',
                'color' => 'indigo',
                'category' => 'pre_match',
            ],
            [
                'name' => 'Map Pool Schedule',
                'url' => route('screens.mappool', ['user_id' => $userId]),
                'icon' => 'heroicon-o-map',
                'description' => 'Round map schedule showing maps list and winners.',
                'color' => 'success',
                'category' => 'pre_match',
            ],
            [
                'name' => 'Point System Rules',
                'url' => route('screens.pointsystem', ['user_id' => $userId]),
                'icon' => 'heroicon-o-list-bullet',
                'description' => 'Placement and elimination points breakdown rules.',
                'color' => 'info',
                'category' => 'pre_match',
            ],
            [
                'name' => 'Tournament Roadmap',
                'url' => route('screens.roadmap', ['user_id' => $userId]),
                'icon' => 'heroicon-o-presentation-chart-line',
                'description' => 'Interactive timeline roadmap for tournament milestones.',
                'color' => 'primary',
                'category' => 'pre_match',
            ],

            // POST-MATCH & LIVE RESULTS
            [
                'name' => 'Live Ranking HUD',
                'url' => route('screens.activematch', ['user_id' => $userId]),
                'icon' => 'heroicon-o-queue-list',
                'description' => 'Real-time scrolling leaderboard HUD for active matches.',
                'color' => 'warning',
                'category' => 'post_match',
            ],
            [
                'name' => 'Map Screen HUD',
                'url' => route('screens.mapscreen', ['user_id' => $userId]),
                'icon' => 'heroicon-o-globe-alt',
                'description' => 'Tactical 1080x1080 map framing showing team locations.',
                'color' => 'info',
                'category' => 'post_match',
            ],
            [
                'name' => 'Head-to-Head Duel',
                'url' => route('screens.headtohead', ['user_id' => $userId]),
                'icon' => 'heroicon-o-users',
                'description' => 'Cinematic diagonal split comparison of top 2 rival teams.',
                'color' => 'warning',
                'category' => 'post_match',
            ],
            [
                'name' => 'Top 5 Fraggers',
                'url' => route('screens.topfraggers', ['user_id' => $userId]),
                'icon' => 'heroicon-o-fire',
                'description' => 'Holographic statistics showcasing top 5 players.',
                'color' => 'danger',
                'category' => 'post_match',
            ],
            [
                'name' => 'Post-Match Stats',
                'url' => route('screens.postmatch', ['user_id' => $userId]),
                'icon' => 'heroicon-o-trophy',
                'description' => 'Placements and statistics from the last completed match.',
                'color' => 'success',
                'category' => 'post_match',
            ],
            [
                'name' => 'Overall Standings',
                'url' => route('screens.overallranking', ['user_id' => $userId]),
                'icon' => 'heroicon-o-chart-bar',
                'description' => 'Tournament-wide overall leaderboard standings.',
                'color' => 'primary',
                'category' => 'post_match',
            ],
            [
                'name' => 'Ending Screen',
                'url' => route('screens.ending', ['user_id' => $userId]),
                'icon' => 'heroicon-o-power',
                'description' => 'Outro closedown with dynamic timer and social feeds.',
                'color' => 'danger',
                'category' => 'post_match',
            ],
            [
                'name' => 'Team Elimination HUD',
                'url' => route('screens.teamelimination', ['user_id' => $userId]),
                'icon' => 'heroicon-o-x-circle',
                'description' => 'Real-time broadcast HUD showing team statuses and eliminations.',
                'color' => 'rose',
                'category' => 'post_match',
            ],
        ];
    }
}
