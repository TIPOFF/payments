<?php

declare(strict_types=1);

namespace Tipoff\Payments\Tests\Unit\Models;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tipoff\Payments\Exceptions\RefundNotAvailableException;
use Tipoff\Payments\Models\Payment;
use Tipoff\Payments\Tests\TestCase;
use Tipoff\Support\Contracts\Payment\RefundInterface;

class PaymentTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function create()
    {
        $model = Payment::factory()->create();
        $this->assertNotNull($model);
    }

    /** @test */
    public function request_refund_no_service()
    {
        /** @var Payment $payment */
        $payment = Payment::factory()->create();

        $this->expectException(RefundNotAvailableException::class);
        $this->expectExceptionMessage('Refund services are not enabled');

        $payment->requestRefund(123, 'Stripe');
    }

    /** @test */
    public function request_refund_with_service()
    {
        $service = \Mockery::mock(RefundInterface::class);
        $service->shouldReceive('createRefund')->andReturnSelf();
        $this->app->instance(RefundInterface::class, $service);

        /** @var Payment $payment */
        $payment = Payment::factory()->create();

        $refund = $payment->requestRefund(123, 'Stripe');
        $this->assertNotNull($refund);
    }
}
