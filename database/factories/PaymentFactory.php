<?php

declare(strict_types=1);

namespace Tipoff\Payments\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Tipoff\Payments\Enums\Gateway;
use Tipoff\Payments\Enums\PaymentSource;
use Tipoff\Payments\Models\Payment;

class PaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'charge_number' => $this->faker->numberBetween(100000, 900000),
            'order_id'      => randomOrCreate(app('order')),
            'user_id'       => randomOrCreate(app('user')),
            'invoice_id'    => randomOrCreate(app('invoice')),
            'amount'        => $this->faker->numberBetween(100, 40000),
            'gateway'       => $this->faker->randomElement(Gateway::getEnumerators()),
            'source'        => $this->faker->randomElement(PaymentSource::getEnumerators()),
            'location_id'   => randomOrCreate(app('location')),
            'creator_id'    => randomOrCreate(app('user')),
            'updater_id'    => randomOrCreate(app('user')),
        ];
    }
}
