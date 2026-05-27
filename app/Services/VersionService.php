<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class VersionService
{
    /**
     * Get the current semantic version.
     * Utilizes rememberForever cache to ensure zero performance overhead on standard web requests.
     */
    public static function current(): string
    {
        return Cache::rememberForever('system_version', function () {
            try {
                if (Schema::hasTable('system_versions')) {
                    $latest = DB::table('system_versions')->latest('id')->first();
                    if ($latest) {
                        return $latest->full_version;
                    }
                }
            } catch (\Throwable $e) {
                // Gracefully fallback on database failures
            }

            return self::determineFromGit();
        });
    }

    /**
     * Calculate version dynamically from local Git environment.
     */
    public static function determineFromGit(): string
    {
        // Base major/minor versions configured here or fall back to skeleton values
        $major = config('app.version_major', '1');
        $minor = config('app.version_minor', '0');

        $patch = 0;
        $hash = 'dev';

        try {
            // Retrieve total commit count to serve as the patch number
            $commitCount = trim(shell_exec('git rev-list --count HEAD') ?? '');
            if (is_numeric($commitCount)) {
                $patch = (int)$commitCount;
            }

            // Retrieve short commit hash
            $lastHash = trim(shell_exec('git log -1 --format="%h"') ?? '');
            if (!empty($lastHash)) {
                $hash = $lastHash;
            }
        } catch (\Throwable $e) {
            // Graceful fallback if Git shell commands are restricted or not found
        }

        return "{$major}.{$minor}.{$patch}-{$hash}";
    }

    /**
     * Determine Git version, store it in database history, and refresh the system cache.
     */
    public static function sync(): string
    {
        $version = self::determineFromGit();

        try {
            if (Schema::hasTable('system_versions')) {
                $parts = [];
                // Parse standard formatted "Major.Minor.Patch-Hash"
                preg_match('/^(\d+)\.(\d+)\.(\d+)(?:-(.+))?$/', $version, $parts);

                DB::table('system_versions')->insert([
                    'major' => $parts[1] ?? 1,
                    'minor' => $parts[2] ?? 0,
                    'patch' => $parts[3] ?? 0,
                    'commit_hash' => $parts[4] ?? null,
                    'full_version' => $version,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } catch (\Throwable $e) {
            // Safe to ignore or fallback
        }

        Cache::forever('system_version', $version);

        return $version;
    }
}
