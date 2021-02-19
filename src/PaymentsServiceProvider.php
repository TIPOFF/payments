<?php

declare(strict_types=1);

namespace Tipoff\Payments;

use Tipoff\Payments\Models\Payment;
use Tipoff\Payments\Policies\PaymentPolicy;
use Tipoff\Support\TipoffPackage;
use Tipoff\Support\TipoffServiceProvider;

class PaymentsServiceProvider extends TipoffServiceProvider
{
    public function configureTipoffPackage(TipoffPackage $package): void
    {
        $package
            ->hasPolicies([
                Payment::class => PaymentPolicy::class,
            ])
            ->hasNovaResources([
                \Tipoff\Payments\Nova\Payment::class,
            ])
            ->name('payments')
            ->hasConfigFile();
    }
}
