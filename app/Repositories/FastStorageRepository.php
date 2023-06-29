<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Redis;

class FastStorageRepository
{
    /**
     *  Save data to fast storage
     * @param $key
     * @param array $data
     * @return boolean
     */
    public function saveDataAndCountIncr($key, $data): bool
    {
        // increment count of key
        Redis::hincrby($key, 'count', 1);
        return Redis::hmset($key, $data);
    }

    /**
     *  Get all keys
     * @param $key
     * @return array
     */
    public function getKeys($key)
    {
        return Redis::keys($key);
    }

    /**
     * get all data from key
     * @param $key
     * @return mixed
     */
    public function getData($key)
    {
        return Redis::hgetall($key);
    }

    /**
     *  Remove key from redis
     * @param $key
     * @return bool
     */
    public function removeKey($key)
    {
        return Redis::del($key);
    }
}
