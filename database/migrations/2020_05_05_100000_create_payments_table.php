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
            $table->foreignIdFor(app('order'))->index();
            $table->foreignIdFor(app('user'));
            $table->foreignIdFor(app('invoice'))->nullable();
            $table->unsignedInteger('amount'); // Amount is in cents.
            $table->unsignedInteger('amount_refunded')->nullable();
            $table->string('method'); // Will need to define these
            $table->string('charge_id')->nullable(); // @todo rename to charge_number

            $table->foreignIdFor(app('user'), 'creator_id');
            $table->foreignIdFor(app('user'), 'updater_id')->nullable();
            $table->timestamps();
        });
    }
}
