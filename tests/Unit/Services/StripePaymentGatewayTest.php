<?php

declare(strict_types=1);

namespace Tipoff\Payments\Tests\Unit\Services;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Cashier\Payment;
use Stripe\PaymentIntent;
use Tipoff\Authorization\Models\User;
use Tipoff\Locations\Models\Location;
use Tipoff\Payments\Exceptions\PaymentChargeException;
use Tipoff\Payments\Services\PaymentGateway\PaymentGateway;
use Tipoff\Payments\Tests\TestCase;

class StripePaymentGatewayTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function charge_no_location_config()
    {
        $user = User::factory()->create();
        $location = Location::factory()->create();

        $service = $this->app->make(PaymentGateway::class);

        $this->expectException(PaymentChargeException::class);
        $this->expectExceptionMessage('Stripe not configured for location.');

        $service->charge($location, $user, 123, ['payment_method_id' => 'abcd']);
    }

    /** @test */
    public function charge_no_stripe_config()
    {
        $user = User::factory()->create();
        $location = Location::factory()->create();

        $service = $this->app->make(PaymentGateway::class);

        $this->expectException(PaymentChargeException::class);
        $this->expectExceptionMessage('Stripe not configured for location.');

        $service->charge($location, $user, 123, ['payment_method_id' => 'abcd']);
    }

    /** @test */
    public function charge_no_paymethod_id()
    {
        $user = User::factory()->create();
        $location = Location::factory()->create();
        config()->set('payments.stripe_keys', [
            'default' => [
                'publishable' => 'DEF_PUB',
                'secret' => 'DEV_SEC',
            ],
        ]);

        $service = $this->app->make(PaymentGateway::class);

        $this->expectException(PaymentChargeException::class);
        $this->expectExceptionMessage('ayment method is required.');

        $service->charge($location, $user, 123, []);
    }

    /** @test */
    public function charge_ok()
    {
        $location = Location::factory()->create();
        config()->set('payments.stripe_keys', [
            'default' => [
                'publishable' => 'DEF_PUB',
                'secret' => 'DEV_SEC',
            ],
        ]);

        $service = $this->app->make(PaymentGateway::class);

        $user = \Mockery::mock(User::class);
        $user->shouldReceive('charge')
            ->withArgs(function ($amount, $paymethod, $other) {
                return $amount === 123 && $paymethod === 'abcd';
            })
            ->once()
            ->andReturn(
                new Payment(new PaymentIntent(['id' => 'ok']))
            );

        $service->charge($location, $user, 123, ['payment_method_id' => 'abcd']);
    }
}
