<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(app('user'));
            $table->unsignedBigInteger('order_id')->index();
            $table->unsignedBigInteger('invoice_id')->nullable()->index();
            $table->unsignedInteger('amount'); // Amount is in cents.
            $table->unsignedInteger('amount_refunded')->nullable();
            $table->string('source');
            $table->string('gateway');
            $table->string('charge_number')->nullable();
            $table->unsignedBigInteger('location_id')->index();

            $table->foreignIdFor(app('user'), 'creator_id');
            $table->foreignIdFor(app('user'), 'updater_id');
            $table->timestamps();
        });
    }
}
