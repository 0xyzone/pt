<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name'          => '1 Day Pass',
                'slug'          => '1-day-pass',
                'description'   => 'Complete access to caster management, players, and real-time HUD overlays for 24 hours. Perfect for one-off events.',
                'price'         => 199,
                'duration_value' => 1,
                'duration_period' => 'days',
                'sort_order'    => 1,
                'features'      => [
                    'max_tournaments'    => 2,
                    'max_teams'          => 12,
                    'max_matches'        => 10,
                    'roadmap_overlay'    => true,
                    'casters_management' => true,
                    'player_management'  => true,
                    'obs_overlays'       => true,
                    'websocket_sync'     => true,
                    'custom_branding'    => true,
                ],
            ],
            [
                'name'          => '1 Week Pass',
                'slug'          => '1-week-pass',
                'description'   => 'Full production access for 7 days. Includes roadmap overlays, live stats editor, and match logs.',
                'price'         => 599,
                'duration_value' => 1,
                'duration_period' => 'weeks',
                'sort_order'    => 2,
                'features'      => [
                    'max_tournaments'    => 5,
                    'max_teams'          => 18,
                    'max_matches'        => 25,
                    'roadmap_overlay'    => true,
                    'casters_management' => true,
                    'player_management'  => true,
                    'obs_overlays'       => true,
                    'websocket_sync'     => true,
                    'custom_branding'    => true,
                ],
            ],
            [
                'name'          => '1 Month Plan',
                'slug'          => '1-month-plan',
                'description'   => 'Standard monthly subscription. Unlocks custom branding ( logos and sponsor layouts) and higher tournament thresholds.',
                'price'         => 1999,
                'duration_value' => 1,
                'duration_period' => 'months',
                'sort_order'    => 3,
                'features'      => [
                    'max_tournaments'    => 15,
                    'max_teams'          => 24,
                    'max_matches'        => 75,
                    'roadmap_overlay'    => true,
                    'casters_management' => true,
                    'player_management'  => true,
                    'obs_overlays'       => true,
                    'websocket_sync'     => true,
                    'custom_branding'    => true,
                ],
            ],
            [
                'name'          => '3 Month Plan',
                'slug'          => '3-month-plan',
                'description'   => 'Quarterly production bundle. High match and team limits for multiple tournament leagues.',
                'price'         => 4999,
                'duration_value' => 3,
                'duration_period' => 'months',
                'sort_order'    => 4,
                'features'      => [
                    'max_tournaments'    => 50,
                    'max_teams'          => 32,
                    'max_matches'        => 250,
                    'roadmap_overlay'    => true,
                    'casters_management' => true,
                    'player_management'  => true,
                    'obs_overlays'       => true,
                    'websocket_sync'     => true,
                    'custom_branding'    => true,
                ],
            ],
            [
                'name'          => '6 Month Plan',
                'slug'          => '6-month-plan',
                'description'   => 'Semi-annual package. Unlimited matches and large team configurations for season-long operations.',
                'price'         => 8999,
                'duration_value' => 6,
                'duration_period' => 'months',
                'sort_order'    => 5,
                'features'      => [
                    'max_tournaments'    => 120,
                    'max_teams'          => 48,
                    'max_matches'        => -1, // unlimited
                    'roadmap_overlay'    => true,
                    'casters_management' => true,
                    'player_management'  => true,
                    'obs_overlays'       => true,
                    'websocket_sync'     => true,
                    'custom_branding'    => true,
                ],
            ],
            [
                'name'          => '1 Year Plan',
                'slug'          => '1-year-plan',
                'description'   => 'Ultimate annual subscription. Complete unlimited access to all features, sync channels, branding, and stats engines.',
                'price'         => 14999,
                'duration_value' => 1,
                'duration_period' => 'years',
                'sort_order'    => 6,
                'features'      => [
                    'max_tournaments'    => -1, // unlimited
                    'max_teams'          => -1, // unlimited
                    'max_matches'        => -1, // unlimited
                    'roadmap_overlay'    => true,
                    'casters_management' => true,
                    'player_management'  => true,
                    'obs_overlays'       => true,
                    'websocket_sync'     => true,
                    'custom_branding'    => true,
                ],
            ],
        ];

        // De-activate or remove old plans first to keep database clean
        Plan::whereNotIn('slug', array_column($plans, 'slug'))->delete();

        foreach ($plans as $planData) {
            Plan::updateOrCreate(['slug' => $planData['slug']], $planData);
        }

        $this->command->info('✅ Plans seeded: 1 Day, 1 Week, 1 Month, 3 Month, 6 Month, 1 Year');
    }
}
