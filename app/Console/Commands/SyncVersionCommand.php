<?php

namespace App\Console\Commands;

use App\Services\VersionService;
use Illuminate\Console\Command;

class SyncVersionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-version';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dynamically determine current Git commit count and hash, store version in DB, and refresh system cache.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting system version synchronization...');

        $version = VersionService::sync();

        $this->info("System version synced successfully! Active Build: v{$version}");
    }
}
