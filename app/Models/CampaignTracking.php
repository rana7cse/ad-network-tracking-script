<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignTracking extends Model
{
    use HasFactory;

    // the table don't have any primary column
    public $timestamps = false;

    protected $table = 'campaign_tracking';

    protected $fillable = [
        "date", "country_code", "type", "campaign_id", "creative_id", "browser_id", "device_id", "count"
    ];

    protected $primaryKey = [
        "date", "country_code", "type", "campaign_id", "creative_id", "browser_id", "device_id"
    ];
}
