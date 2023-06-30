<?php

namespace Tests\Unit;

use App\Services\IpStackService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Illuminate\Support\Facades\Facade;


class IPStackServiceTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();

        $this->afterApplicationCreated(function () {
            Facade::clearResolvedInstances();
        });
    }

    public function test_get_country_info()
    {
        Http::fake([
            'api.ipstack.com/*' => Http::response([
                'country_code' => 'BD',
                'country_name' => 'Bangladesh',
            ], 200),
        ]);


        Cache::shouldReceive('remember')
            ->once()
            ->andReturnUsing(function ($key, $expiration, $callback) {
                $this->assertEquals('127.0.0.1', $key);
                return $callback();
            });


        $service = new IpStackService();

        $result = $service->getCountryInfo('127.0.0.1');

        $this->assertEquals([
            'country_code' => 'BD',
            'country_name' => 'Bangladesh'
        ], $result);
    }

    public function test_get_country_info_hits_cache()
    {
        $inputIpInfo = [
            'country_code' => 'BD',
            'country_name' => 'Bangladesh',
        ];

        Cache::shouldReceive('remember')
            ->once()
            ->withArgs(['127.0.0.1', \Mockery::type('DateTime'), \Mockery::type('Closure')])
            ->andReturn($inputIpInfo);


        Http::shouldReceive('get')->never();

        $service = new IpStackService();
        $result = $service->getCountryInfo('127.0.0.1');

        $this->assertEquals($inputIpInfo, $result);
    }

    public function test_get_country_info_misses_cache()
    {
        $mockInputData = [
            'country_code' => 'BD',
            'country_name' => 'Bangladesh',
        ];

        Http::fake([
            'api.ipstack.com/*' => Http::response($mockInputData, 200),
        ]);

        Cache::shouldReceive('remember')
            ->once()
            ->withArgs(['127.0.0.1', \Mockery::type('DateTime'), \Mockery::type('Closure')])
            ->andReturnUsing(function ($key, $expiration, $callback) use ($mockInputData) {
                return $callback();
            });

        $service = new IpStackService();
        $result = $service->getCountryInfo('127.0.0.1');

        $this->assertEquals($mockInputData, $result);
    }
}
