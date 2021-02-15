<?php

declare(strict_types=1);

namespace Tipoff\Payments\Tests;

use Tipoff\Payments\PaymentsServiceProvider;
use Tipoff\Support\SupportServiceProvider;
use Tipoff\TestSupport\BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            SupportServiceProvider::class,
            PaymentsServiceProvider::class,
        ];
    }
}
