<?php

namespace Tests\Unit;

use App\Repositories\FastStorageRepository;
use App\Services\CampaignTrackingService;
use App\Services\IpStackService;
use PHPUnit\Framework\TestCase;

class CampaignTrackingServiceTest extends TestCase
{

    public function test_save_data_to_fast_store()
    {
        // Create mock classes and call dependent methods
        $mockIpStackService = \Mockery::mock(IpStackService::class);
        $mockFSRepository = \Mockery::mock(FastStorageRepository::class);

        $mockIpStackService->shouldReceive('getCountryInfo')->once()->andReturn([
            'country_code' => 'BD'
        ]);

        $mockFSRepository->shouldReceive('saveDataAndCountIncr')->once()->andReturn(true);

        // Create campaign tracking service instance
        $ctService = new CampaignTrackingService($mockIpStackService, $mockFSRepository);

        $data = [
            'campaignId' => 4235,
            'creativeId' => 23423,
            'browserId' => 5,
            'deviceId' => 8,
            'clientIp' => '78.60.201.201'
        ];

        $this->assertTrue(
            $ctService->storeDataToFS($data)
        );
    }


    public function test_get_all_campaign_keys()
    {
        // Create mock classes and call dependent methods
        $mockIpStackService = \Mockery::mock(IpStackService::class);
        $mockFSRepository = \Mockery::mock(FastStorageRepository::class);

        $mockFSRepository->shouldReceive('getKeys')->once()->andReturn([
            'campaign:1', 'campaign:2'
        ]);

        // Instantiate CampaignTrackingService with mock objects
        $service = new CampaignTrackingService($mockIpStackService, $mockFSRepository);

        $this->assertEquals(['campaign:1', 'campaign:2'], $service->getAllCampaignKeys());
    }

    public function test_get_data_by_key()
    {
        $mockIpStackService = \Mockery::mock(IpStackService::class);
        $mockFSRepository = \Mockery::mock(FastStorageRepository::class);

        $mockFSRepository->shouldReceive('getData')->once()->andReturn([
            'campaignId' => 100,
            'creativeId' => 200,
            'browserId' => 300,
            'deviceId' => 400
        ]);

        $ctService = new CampaignTrackingService($mockIpStackService, $mockFSRepository);

        $this->assertEquals(
            [
                'campaignId' => 100,
                'creativeId' => 200,
                'browserId' => 300,
                'deviceId' => 400
            ],
            $ctService->getDataByKey('campaign:1')
        );
    }
}
