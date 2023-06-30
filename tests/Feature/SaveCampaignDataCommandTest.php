<?php

namespace Tests\Feature;

use App\Services\CampaignTrackingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class SaveCampaignDataCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_campaign_data_save_command(): void
    {
        $mockData = [
            'date' => '2022-06-26',
            'campaign_id' => 4235,
            'creative_id' => 23423,
            'browser_id' => 5,
            'type' => 1,
            'country_code' => 'DB',
            'device_id' => 20,
            'count' => 10
        ];

        $this->partialMock(CampaignTrackingService::class, function ($mock) use ($mockData) {
            $mock->shouldReceive('getAllCampaignKeys')
                ->once()
                ->andReturn(['campaign:mockKey']);

            $mock->shouldReceive('getDataByKey')
                ->once()
                ->with('campaign:mockKey')
                ->andReturn($mockData);
        });

        Artisan::call('campaignData:save');

        $this->assertDatabaseHas('campaign_tracking', $mockData);
    }
}
