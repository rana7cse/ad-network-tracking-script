<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class IpStackService
{
    const API_URL = "http://api.ipstack.com/";

    protected $apiKey;

    public function __construct($apiKey = null)
    {
        $this->apiKey = $apiKey ?? config('adscript.ip_stack_api_key');
    }

    /**
     *  Find ip address by creating a request on ipStack api service
     * @param $ipAddress
     * @return array|mixed|null
     */
    public function getIpInfo($ipAddress)
    {
        $url = self::API_URL."{$ipAddress}?access_key={$this->apiKey}";

        try {
            $response = Http::get($url);
            return $response->json();
        } catch (\Exception $exception) {
            return null;
        }
    }

    /**
     *  Get only country data from this function
     * @param $ipAddress
     * @return array|mixed|null
     */
    public function getCountryInfo($ipAddress): mixed
    {
        return Cache::remember($ipAddress, Carbon::now()->addDays(30), function () use ($ipAddress){
           $ipInfo = $this->getIpInfo($ipAddress);
           // return null if don't found any data
           if (blank($ipInfo)) return null;

           return Arr::only($ipInfo, ['country_code', 'country_name']);
        });
    }
}
