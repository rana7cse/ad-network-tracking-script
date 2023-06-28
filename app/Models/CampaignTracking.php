<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignTracking extends Model
{
    use HasFactory;

    // the table don't have any primary column
    public $timestamps = false;

    protected $fillable = [
        "date", "country_code", "tracking_type", "campaign_id", "creative_id", "browser_id", "device_id"
    ];

    protected $primaryKey = [
        "date", "country_code", "tracking_type", "campaign_id", "creative_id", "browser_id", "device_id"
    ];
}
