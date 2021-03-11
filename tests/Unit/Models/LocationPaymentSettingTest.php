<?php

declare(strict_types=1);

namespace Tipoff\Payments\Tests\Unit\Models;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tipoff\Locations\Models\Location;
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

    /** @test */
    public function db_stripe_values_used_if_set()
    {
        /** @var LocationPaymentSetting $settings */
        $settings = LocationPaymentSetting::factory()->create([
            'stripe_publishable' => 'PUB',
            'stripe_secret' => 'SEC',
            'location_id' => Location::factory()->create([
                'abbreviation' => 'ABC',
            ]),
        ]);

        $this->assertEquals('PUB', $settings->stripe_publishable);
        $this->assertEquals('SEC', $settings->stripe_secret);
    }

    /** @test */
    public function config_stripe_values_used_if_db_null()
    {
        /** @var LocationPaymentSetting $settings */
        $settings = LocationPaymentSetting::factory()->create([
            'stripe_publishable' => null,
            'stripe_secret' => null,
            'location_id' => Location::factory()->create([
                'abbreviation' => 'ABC',
            ]),
        ]);

        config()->set('payments.stripe_keys', [
            'default' => [
                'publishable' => 'DEF_PUB',
                'secret' => 'DEV_SEC',
            ],
            'locations' => [
                'ABC' => [
                    'publishable' => 'ABC_PUB',
                    'secret' => 'ABC_SEC',
                ]
            ],
        ]);

        $this->assertEquals('ABC_PUB', $settings->stripe_publishable);
        $this->assertEquals('ABC_SEC', $settings->stripe_secret);
    }

    /** @test */
    public function config_default_stripe_values_used_if_db_null_and_no_location()
    {
        /** @var LocationPaymentSetting $settings */
        $settings = LocationPaymentSetting::factory()->create([
            'stripe_publishable' => null,
            'stripe_secret' => null,
            'location_id' => Location::factory()->create([
                'abbreviation' => 'AAA',
            ]),
        ]);

        config()->set('payments.stripe_keys', [
            'default' => [
                'publishable' => 'DEF_PUB',
                'secret' => 'DEV_SEC',
            ],
            'locations' => [
                'ABC' => [
                    'publishable' => 'ABC_PUB',
                    'secret' => 'ABC_SEC',
                ]
            ],
        ]);

        $this->assertEquals('DEF_PUB', $settings->stripe_publishable);
        $this->assertEquals('DEV_SEC', $settings->stripe_secret);
    }

    /** @test */
    public function config_with_no_default_returns_null()
    {
        /** @var LocationPaymentSetting $settings */
        $settings = LocationPaymentSetting::factory()->create([
            'stripe_publishable' => null,
            'stripe_secret' => null,
            'location_id' => Location::factory()->create([
                'abbreviation' => 'AAA',
            ]),
        ]);

        config()->set('payments.stripe_keys', [
            'locations' => [
                'ABC' => [
                    'publishable' => 'ABC_PUB',
                    'secret' => 'ABC_SEC',
                ]
            ],
        ]);

        $this->assertNull($settings->stripe_publishable);
        $this->assertNull($settings->stripe_secret);
    }
}
