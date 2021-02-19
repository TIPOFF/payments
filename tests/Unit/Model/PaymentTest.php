<?php

declare(strict_types=1);

namespace Tipoff\Payments\Tests\Unit\Models;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tipoff\Payments\Tests\TestCase;
use Tipoff\Payments\Models\Payment;

class PaymentTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function create()
    {
        $model = Payment::factory()->create();
        $this->assertNotNull($model);
    }
}
