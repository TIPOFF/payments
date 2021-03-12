<?php

declare(strict_types=1);

namespace Tipoff\Payments\Services\PaymentGateway;

use Exception;
use Stripe\Stripe;
use Tipoff\Authorization\Models\User;
use Tipoff\Locations\Models\Location;
use Tipoff\Payments\Exceptions\PaymentChargeException;
use Tipoff\Payments\Objects\PaymentSettings;

class StripePaymentGateway implements PaymentGateway
{
    public function charge(Location $location, User $user, int $amount, array $options = []): object
    {
        $paymentSettings = PaymentSettings::forLocation($location);
        if (! $paymentSettings->getStripeSecret()) {
            throw new PaymentChargeException('Stripe not configured for location.');
        }

        if (empty($options['payment_method_id'])) {
            throw new PaymentChargeException('Payment method is required.');
        }

        try {
            Stripe::setApiKey($paymentSettings->getStripeSecret());

            return $user->charge($amount, $options['payment_method_id'], [
                'description' => $options['description'] ?? '',
            ]);
        } catch (Exception $exception) {
            throw new PaymentChargeException($exception->getMessage());
        }
    }
}
