<?php

declare(strict_types=1);

namespace Tipoff\Payments\Tests\Unit\Objects;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tipoff\Locations\Models\Location;
use Tipoff\Payments\Objects\PaymentSettings;
use Tipoff\Payments\Tests\TestCase;

class PaymentSettingsTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function config_stripe_values_used()
    {
        config()->set('payments.stripe_keys', [
            'default' => [
                'publishable' => 'DEF_PUB',
                'secret' => 'DEV_SEC',
            ],
            'locations' => [
                'ABC' => [
                    'publishable' => 'ABC_PUB',
                    'secret' => 'ABC_SEC',
                ],
            ],
        ]);

        $location = Location::factory()->create([
            'abbreviation' => 'ABC',
        ]);

        $settings = PaymentSettings::forLocation($location);

        $this->assertEquals('ABC_PUB', $settings->getStripePublishable());
        $this->assertEquals('ABC_SEC', $settings->getStripeSecret());
    }

    /** @test */
    public function config_default_stripe_values_used_if_db_null_and_no_location()
    {
        config()->set('payments.stripe_keys', [
            'default' => [
                'publishable' => 'DEF_PUB',
                'secret' => 'DEV_SEC',
            ],
            'locations' => [
                'ABC' => [
                    'publishable' => 'ABC_PUB',
                    'secret' => 'ABC_SEC',
                ],
            ],
        ]);

        $location = Location::factory()->create([
            'abbreviation' => 'AAA',
        ]);

        $settings = PaymentSettings::forLocation($location);

        $this->assertEquals('DEF_PUB', $settings->getStripePublishable());
        $this->assertEquals('DEV_SEC', $settings->getStripeSecret());
    }

    /** @test */
    public function config_with_no_default_returns_null()
    {
        config()->set('payments.stripe_keys', [
            'locations' => [
                'ABC' => [
                    'publishable' => 'ABC_PUB',
                    'secret' => 'ABC_SEC',
                ],
            ],
        ]);

        $location = Location::factory()->create([
            'abbreviation' => 'AAA',
        ]);

        $settings = PaymentSettings::forLocation($location);

        $this->assertNull($settings->getStripePublishable());
        $this->assertNull($settings->getStripeSecret());
    }
}
