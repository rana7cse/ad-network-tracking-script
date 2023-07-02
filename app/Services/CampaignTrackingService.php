<?php

namespace App\Services;

use App\Repositories\FastStorageRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redis;

class CampaignTrackingService
{
    public function __construct(
        protected IpStackService $ipStackService,
        protected FastStorageRepository $fsRepository
    ){}

    /**
     * Save data to fast storage
     * @param array $data
     * @return bool
     */
    public function storeDataToFS(array $data) : bool
    {
        // get country info by client ip
        $countryInfo = $this->ipStackService->getCountryInfo(
            data_get($data, 'client_ip')
        );
        // add country code to existing data
        $data['country_code'] = !blank($countryInfo) ? $countryInfo['country_code'] : 'default';
        // remove client ip from data
        unset($data['client_ip']);
        // generate cache key for redis hash
        $key = $this->getCacheKey($data, 'campaign');
        // saving data to redis Hash data type
        return $this->fsRepository->saveDataAndCountIncr($key, $data);
    }

    /**
     * Get all keys
     * @return array
     */
    public function getAllCampaignKeys()
    {
        return $this->fsRepository->getKeys('campaign:*');
    }

    /**
     * Get all keys
     * @param $key
     * @return array
     */
    public function getDataByKey($key): array
    {
        return $this->fsRepository->getData($key);
    }

    /**
     * Remove key data
     * @param $key
     * @return bool
     */
    public function removeDataByKey($key): bool
    {
        return $this->fsRepository->removeKey($key);
    }

    /**
     * Generate key for fast storage hash
     * @param array $data
     * @param string $prefix
     * @return string
     */
    public function getCacheKey(array $data, $prefix = 'ad'): string
    {
        $keys = array_keys($data);

        $values = Arr::map(Arr::sort($keys), function ($key) use ($data){
            return $data[$key];
        });

        return "{$prefix}:".implode(":", $values);
    }
}
