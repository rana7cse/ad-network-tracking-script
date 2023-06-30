<?php

namespace App\Http\Controllers;

use App\Enum\TrackingType;
use App\Jobs\StoreCampaignTrackingData;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

        dispatch(new StoreCampaignTrackingData($campaignData))->onQueue('track');

        return response()->json([], 200);
    }
}
