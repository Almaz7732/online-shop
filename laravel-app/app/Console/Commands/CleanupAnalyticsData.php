<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProductView;
use App\Models\SiteVisit;
use Carbon\Carbon;

class CleanupAnalyticsData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'analytics:cleanup {--days=365 : Number of days to keep}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup old analytics data (product views and site visits)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $cutoffDate = Carbon::now()->subDays($days);

        $this->info("Cleaning up analytics data older than {$days} days (before {$cutoffDate->format('Y-m-d')})...");

        // Delete old product views
        $productViewsDeleted = ProductView::where('viewed_at', '<', $cutoffDate)->delete();
        $this->info("Deleted {$productViewsDeleted} product view records.");

        // Delete old site visits
        $siteVisitsDeleted = SiteVisit::where('visited_at', '<', $cutoffDate)->delete();
        $this->info("Deleted {$siteVisitsDeleted} site visit records.");

        $this->info('Analytics cleanup completed successfully!');

        return 0;
    }
}
