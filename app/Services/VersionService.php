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
     * Calculate version dynamically from local Git environment and database history.
     */
    public static function determineFromGit(): string
    {
        // Base major/minor versions configured here or fall back to skeleton values
        $major = (int)config('app.version_major', '1');
        $minor = (int)config('app.version_minor', '0');

        $patch = 0;
        $hash = 'dev';

        try {
            // Retrieve short commit hash
            $lastHash = trim(shell_exec('git log -1 --format="%h"') ?? '');
            if (!empty($lastHash)) {
                $hash = $lastHash;
            }
        } catch (\Throwable $e) {
            // Graceful fallback if Git shell commands are restricted or not found
        }

        try {
            if (Schema::hasTable('system_versions')) {
                // Find the latest sync record for the CURRENT major/minor combination
                $latestForVersion = DB::table('system_versions')
                    ->where('major', $major)
                    ->where('minor', $minor)
                    ->latest('id')
                    ->first();

                if ($latestForVersion) {
                    $prevPatch = (int)$latestForVersion->patch;
                    $prevHash = $latestForVersion->commit_hash;

                    if ($hash !== 'dev' && !empty($prevHash)) {
                        // If Git is available and we have a valid previous commit hash,
                        // count commits since that previous commit to determine the increment.
                        $diffCount = trim(shell_exec("git rev-list --count {$prevHash}..HEAD") ?? '');
                        if (is_numeric($diffCount)) {
                            $patch = $prevPatch + (int)$diffCount;
                        } else {
                            // If the previous hash is not in history (e.g. rebase/squash),
                            // increment patch by 1 if hash has changed, otherwise keep prev patch.
                            $patch = ($hash === $prevHash) ? $prevPatch : ($prevPatch + 1);
                        }
                    } else {
                        // Fallback if Git hash is not available or previous hash was empty.
                        // Increment by 1 if we're doing a new sync/build.
                        $patch = $prevPatch + 1;
                    }
                } else {
                    // If no previous record exists for this major/minor, start patch at 0!
                    $patch = 0;
                }
            } else {
                // Fall back to total commit count if database table does not exist
                $patch = self::getTotalCommitCount();
            }
        } catch (\Throwable $e) {
            // Fall back to total commit count if database query fails
            $patch = self::getTotalCommitCount();
        }

        return "{$major}.{$minor}.{$patch}";
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
                // Parse standard formatted "Major.Minor.Patch"
                preg_match('/^(\d+)\.(\d+)\.(\d+)$/', $version, $parts);

                // Retrieve short commit hash to store in DB
                $commitHash = null;
                try {
                    $commitHash = trim(shell_exec('git log -1 --format="%h"') ?? '') ?: null;
                } catch (\Throwable $e) {
                    // Ignore
                }

                DB::table('system_versions')->insert([
                    'major' => $parts[1] ?? 1,
                    'minor' => $parts[2] ?? 0,
                    'patch' => $parts[3] ?? 0,
                    'commit_hash' => $commitHash,
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

    /**
     * Get the total number of commits in the current Git branch.
     */
    private static function getTotalCommitCount(): int
    {
        try {
            $commitCount = trim(shell_exec('git rev-list --count HEAD') ?? '');
            if (is_numeric($commitCount)) {
                return (int)$commitCount;
            }
        } catch (\Throwable $e) {
            // Ignore
        }
        return 0;
    }
}
