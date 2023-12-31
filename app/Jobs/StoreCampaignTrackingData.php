<?php

namespace App\Jobs;

use App\Services\CampaignTrackingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StoreCampaignTrackingData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected $campaignData
    )
    {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $trackingService = app(CampaignTrackingService::class);
        // Store data to fast storage
        $trackingService->storeDataToFS($this->campaignData);
    }
}
