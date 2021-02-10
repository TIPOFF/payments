<?php

namespace Tipoff\Payments\Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class PaymentsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('payments')->truncate();

        DB::table('payments')->insert([
            0 =>
            [
                'amount' => 290,
                'amount_refunded' => NULL,
                'charge_id' => 'pi_1HUyPTALjg9MLkjRT6INT43D',
                'created_at' => '2020-09-24 17:39:15',
                'creator_id' => 16,
                'customer_id' => 1,
                'id' => 1,
                'invoice_id' => NULL,
                'method' => 'Stripe',
                'order_id' => 1,
                'updated_at' => '2020-09-24 17:39:15',
                'updater_id' => 16,
            ],
        ]);
    }
}
