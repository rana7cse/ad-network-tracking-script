<?php

use App\Enum\TrackingType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('campaign_tracking', function (Blueprint $table) {
            $table->date('date');
            $table->string('country_code', 3);
            $table->tinyInteger('type')->default(TrackingType::IMPRESSION->value);
            $table->unsignedBigInteger('campaign_id');
            $table->unsignedBigInteger('creative_id');
            $table->unsignedBigInteger('browser_id');
            $table->unsignedBigInteger('device_id');
            $table->unsignedBigInteger('count');
            // Composite primary key
            $table->primary([
                "date", "country_code", "type", "campaign_id", "creative_id", "browser_id", "device_id"
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_tracking');
    }
};
