<?php

declare(strict_types=1);

namespace Tipoff\Payments\Tests;

use Laravel\Nova\NovaCoreServiceProvider;
use Spatie\Permission\PermissionServiceProvider;
use Tipoff\Addresses\AddressesServiceProvider;
use Tipoff\Authorization\AuthorizationServiceProvider;
use Tipoff\Checkout\CheckoutServiceProvider;
use Tipoff\Invoices\InvoicesServiceProvider;
use Tipoff\Locations\LocationsServiceProvider;
use Tipoff\Payments\PaymentsServiceProvider;
use Tipoff\Support\SupportServiceProvider;
use Tipoff\TestSupport\BaseTestCase;
use Tipoff\TestSupport\Providers\NovaPackageServiceProvider;
use Tipoff\Statuses\StatusesServiceProvider;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            SupportServiceProvider::class,
            AuthorizationServiceProvider::class,
            PermissionServiceProvider::class,
            PaymentsServiceProvider::class,
            InvoicesServiceProvider::class,
            AddressesServiceProvider::class,
            LocationsServiceProvider::class,
            CheckoutServiceProvider::class,
            NovaCoreServiceProvider::class,
            NovaPackageServiceProvider::class,
            StatusesServiceProvider::class,
        ];
    }
}
