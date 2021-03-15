<?php

declare(strict_types=1);

namespace Tipoff\Payments\Tests\Unit\Services;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tipoff\Authorization\Models\User;
use Tipoff\Locations\Models\Location;
use Tipoff\Payments\Enums\Gateway;
use Tipoff\Payments\Enums\PaymentSource;
use Tipoff\Payments\Models\Payment;
use Tipoff\Payments\Services\PaymentGateway\PaymentGateway;
use Tipoff\Payments\Tests\TestCase;

class CreatePaymentTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();

        $service = \Mockery::mock(PaymentGateway::class);
        $service->shouldReceive('charge')->andReturn('ok');
        $service->shouldReceive('getGatewayType')->andReturn(Gateway::STRIPE());
        $this->app->instance(PaymentGateway::class, $service);
    }

    /** @test */
    public function create_payment()
    {
        $user = User::factory()->create();
        $location = Location::factory()->create();

        $payment = Payment::createPayment($location->id, $user, 1234, 'paymethod', 'online');
        $this->assertEquals('ok', $payment->charge_number);
        $this->assertEquals(1234, $payment->amount);
        $this->assertEquals(PaymentSource::ONLINE, $payment->source->getValue());
        $this->assertEquals(Gateway::STRIPE, $payment->gateway->getValue());
        $this->assertNull($payment->id);
    }
}
