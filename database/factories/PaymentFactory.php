<?php 

declare(strict_types=1);

namespace Tipoff\Payments\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
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
            'charge_id'   => $this->faker->numberBetween(100000, 900000),
            'order_id'    => randomOrCreate(app('order')),
            'customer_id' => randomOrCreate(app('customer')),
            'invoice_id'  => randomOrCreate(app('invoice')),
            'amount'      => $this->faker->numberBetween(100, 40000),
            'method'      => $this->faker->randomElement(['online', 'phone', 'in-person']),
            'creator_id'  => randomOrCreate(app('user')),
            'updater_id'  => randomOrCreate(app('user'))
        ];
    }
}
