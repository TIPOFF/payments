<?php namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Tipoff\Support\Support;
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
            'order_id'    => Support::randomOrCreate(config('payments.order_model')),
            'customer_id' => Support::randomOrCreate(config('payments.customer_model')),
            'invoice_id'  => Support::randomOrCreate(config('payments.invoice_model')),
            'amount'      => $this->faker->numberBetween(100, 40000),
            'method'      => $this->faker->randomElement(['online', 'phone', 'in-person']),
            'creator_id'  => Support::randomOrCreate(config('payments.user_model')),
            'updater_id'  => Support::randomOrCreate(config('payments.user_model')),
        ];
    }
}
