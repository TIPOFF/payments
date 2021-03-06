<?php

declare(strict_types=1);

namespace Tipoff\Payments\Tests;

use Laravel\Nova\NovaCoreServiceProvider;
use Spatie\Permission\PermissionServiceProvider;
use Tipoff\Authorization\AuthorizationServiceProvider;
use Tipoff\Locations\LocationsServiceProvider;
use Tipoff\Payments\PaymentsServiceProvider;
use Tipoff\Payments\Tests\Support\Providers\NovaPackageServiceProvider;
use Tipoff\Support\SupportServiceProvider;
use Tipoff\TestSupport\BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            SupportServiceProvider::class,
            AuthorizationServiceProvider::class,
            PermissionServiceProvider::class,
            PaymentsServiceProvider::class,
            LocationsServiceProvider::class,
            NovaCoreServiceProvider::class,
            NovaPackageServiceProvider::class,
        ];
    }
}
