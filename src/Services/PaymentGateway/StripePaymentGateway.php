<?php

declare(strict_types=1);

namespace Tipoff\Payments\Services\PaymentGateway;

use Tipoff\Authorization\Models\User;
use Tipoff\Locations\Models\Location;
use Exception;
use Stripe\Stripe;
use Tipoff\Payments\Exceptions\PaymentChargeException;
use Tipoff\Payments\Models\LocationPaymentSetting;

class StripePaymentGateway implements PaymentGateway
{
    public function charge(Location $location, User $user, int $amount, array $options = []): object
    {
        if ($paymentSettings = LocationPaymentSetting::forLocation($location)) {
            try {
                Stripe::setApiKey($paymentSettings->stripe_secret);

                return $user->charge($amount, $options['payment_method_id'], [
                    'description' => $options['description'] ?? '',
                ]);
            } catch (Exception $exception) {
                throw new PaymentChargeException($exception->getMessage());
            }
        }

        throw new PaymentChargeException('Stripe not configured for location');
    }
}
