<?php

namespace Tests\Feature;

use App\Enum\TrackingType;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Tests\TestCase;

class CampaignTrackerTest extends TestCase
{
    use RefreshDatabase;

    public function test_main_campaign_tracker_aka_url_track(): void
    {
        $data = [
            'cid' => 4235,
            'crid' => 23423,
            'bid' => 5,
            'did' => 8,
            'cip' => '78.60.201.201',
            'conv' => 'imp',
        ];

        $response = $this->get('/track?' . http_build_query($data));

        $response->assertStatus(200);

        $this->assertDataStoredInRedis($data);
    }

    private function assertDataStoredInRedis($data)
    {
        $trData = [
            'campaign_id' => data_get($data,'cid'),
            'creative_id' => data_get($data,'crid'),
            'browser_id' => data_get($data,'bid'),
            'device_id' => data_get($data,'did'),
            'client_ip' => data_get($data,'cip'),
            'type' => 1,
            'date' => '2023-06-30'
        ];

        $trackingService = app(\App\Services\CampaignTrackingService::class);

        $trackingService->storeDataToFS($trData);

        $storedData = $trackingService->getDataByKey('campaign:5:4235:LT:23423:2023-06-30:8:1');

        $this->assertEquals(
            Arr::except($trData, ['client_ip']),
            Arr::except($storedData, ['count', 'country_code'])
        );
    }
}
