<?php

declare(strict_types=1);

namespace Tipoff\Payments;

use Tipoff\Support\TipoffPackage;
use Tipoff\Support\TipoffServiceProvider;

class PaymentsServiceProvider extends TipoffServiceProvider
{
    public function configureTipoffPackage(TipoffPackage $package): void
    {
        $package
            ->name('payments')
            ->hasConfigFile();
    }
}
