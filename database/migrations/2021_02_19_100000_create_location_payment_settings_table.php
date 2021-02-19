<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tipoff\Locations\Models\Location;

class CreateLocationPaymentSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('location_payment_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Location::class)->unique();	// NOTE - unique() added -- there should be exactly one record per location!
            $table->string('stripe_publishable')->nullable()->unique();
            $table->text('stripe_secret')->nullable();
            $table->foreignIdFor(app('user'), 'creator_id');
            $table->foreignIdFor(app('user'), 'updater_id');
            $table->timestamps();
        });
    }
}
