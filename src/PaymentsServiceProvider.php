<?php

namespace Tipoff\Payments;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Tipoff\Payments\Commands\PaymentsCommand;

class PaymentsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('payments')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_payments_table')
            ->hasCommand(PaymentsCommand::class);
    }
}
