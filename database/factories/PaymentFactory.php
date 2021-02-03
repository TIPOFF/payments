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
            'order_id'    => Support::randomOrCreate(config('payments.model_class.order')),
            'customer_id' => Support::randomOrCreate(config('payments.model_class.customer')),
            'invoice_id'  => Support::randomOrCreate(config('payments.model_class.invoice')),
            'amount'      => $this->faker->numberBetween(100, 40000),
            'method'      => $this->faker->randomElement(['online', 'phone', 'in-person']),
            'creator_id'  => Support::randomOrCreate(config('payments.model_class.user')),
            'updater_id'  => Support::randomOrCreate(config('payments.model_class.user')),
        ];
    }
}
