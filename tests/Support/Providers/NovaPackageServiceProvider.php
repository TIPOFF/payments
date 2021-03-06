<?php

declare(strict_types=1);

namespace Tipoff\Payments\Tests\Support\Providers;

use Tipoff\Payments\Nova\Payment;
use Tipoff\TestSupport\Providers\BaseNovaPackageServiceProvider;

class NovaPackageServiceProvider extends BaseNovaPackageServiceProvider
{
    public static array $packageResources = [
        Payment::class,
    ];
}
