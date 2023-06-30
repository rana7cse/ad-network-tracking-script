<?php

namespace App\Http\Controllers;

use App\Enum\TrackingType;
use App\Jobs\StoreCampaignTrackingData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CampaignTrackingController extends Controller
{
    public function tracker(Request $request)
    {
        $campaignData = [
            'campaign_id' => $request->get('cid'),
            'creative_id' => $request->get('crid'),
            'browser_id' => $request->get('bid'),
            'device_id' => $request->get('did'),
            'client_ip' => $request->get('cip'),
            'type' => TrackingType::getEnumValueByConv($request->get('conv')),
            'date' => Carbon::now()->format('Y-m-d')
        ];

        /*
         *  Added these lines to send dummy image response
         * */
        $imageUrl = 'https://placehold.co/600x400/png';

        $imageContent = Cache::remember($imageUrl, Carbon::now()->addDays(10), function () use ($imageUrl) {
          return file_get_contents($imageUrl);
        });

        // Send data to background queue job
        dispatch(new StoreCampaignTrackingData($campaignData))->onQueue('track');

        return response($imageContent, 200, [
            'Content-Type' => 'image/png',
            'Content-Length' => strlen($imageContent),
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
        ]);
    }
}
