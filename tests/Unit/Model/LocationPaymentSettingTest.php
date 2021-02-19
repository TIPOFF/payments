<?php

declare(strict_types=1);

namespace Tipoff\Payments\Tests\Unit\Models;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tipoff\Payments\Models\LocationPaymentSetting;
use Tipoff\Payments\Tests\TestCase;

class LocationPaymentSettingTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function create()
    {
        $model = LocationPaymentSetting::factory()->create();
        $this->assertNotNull($model);
    }
}
