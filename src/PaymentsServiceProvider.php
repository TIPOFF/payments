<?php

declare(strict_types=1);

namespace Tipoff\Payments;

use Tipoff\Payments\Models\Payments;
use Tipoff\Payments\Policies\PaymentsPolicy;
use Tipoff\Support\TipoffPackage;
use Tipoff\Support\TipoffServiceProvider;

class PaymentsServiceProvider extends TipoffServiceProvider
{
    public function configureTipoffPackage(TipoffPackage $package): void
    {
        $package
            ->hasPolicies([
                Payments::class => PaymentsPolicy::class,
            ])
            ->name('payments')
            ->hasConfigFile();
    }
}
