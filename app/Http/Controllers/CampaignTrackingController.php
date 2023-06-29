<?php

namespace App\Http\Controllers;

use App\Enum\TrackingType;
use App\Jobs\StoreCampaignTrackingData;
use Illuminate\Http\Request;

class CampaignTrackingController extends Controller
{
    public function tracker(Request $request)
    {
        $requestData = [
            'campaign_id' => $request->get('cid'),
            'creative_id' => $request->get('crid'),
            'browser_id' => $request->get('bid'),
            'device_id' => $request->get('did'),
            'client_ip' => $request->get('cip'),
            'type' => TrackingType::getEnumValueByConv($request->get('conv'))
        ];

        dispatch(new StoreCampaignTrackingData($requestData))->onQueue('track');

        return response()->json([
            'msg' => 'wow'
        ]);
    }
}
