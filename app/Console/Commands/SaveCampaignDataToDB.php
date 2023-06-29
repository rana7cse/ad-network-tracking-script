<?php

namespace App\Console\Commands;

use App\Models\CampaignTracking;
use App\Services\CampaignTrackingService;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class SaveCampaignDataToDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'campaignData:save';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Aggregate campaign data from Redis to campaign_tracking table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $trackingService = app(CampaignTrackingService::class);
        $keys = $trackingService->getAllCampaignKeys();
        foreach ($keys as $key) {
            $key = str_replace(config('database.redis.options.prefix'), "", $key);
            $campaignData = $trackingService->getDataByKey($key);
            DB::table('campaign_tracking')->updateOrInsert(
                Arr::except($campaignData,['count']),
                ['count' => $campaignData['count']]
            );
        }
    }
}
