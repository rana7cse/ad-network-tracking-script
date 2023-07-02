<?php

namespace App\Console\Commands;

use App\Services\CampaignTrackingService;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

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

    protected CampaignTrackingService $trackingService;

    public function __construct(CampaignTrackingService $trackingService)
    {
        parent::__construct();
        $this->trackingService = $trackingService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $keys = $this->trackingService->getAllCampaignKeys();
        foreach ($keys as $key) {
            $this->processKey($key);
        }
    }

    private function processKey($key)
    {
        $key = str_replace(config('database.redis.options.prefix'), "", $key);
        $campaignData = $this->trackingService->getDataByKey($key);
        // Campaign data saving to db
        DB::table('campaign_tracking')->updateOrInsert(
            Arr::except($campaignData, ['count']),
            ['count' => $campaignData['count']]
        );

        // Remove key if it's require to clean data
        // $this->trackingService->removeDataByKey($key);
    }
}
