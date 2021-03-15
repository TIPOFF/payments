<?php

declare(strict_types=1);

namespace Tipoff\Payments\Services\PaymentGateway;

use Exception;
use Stripe\Stripe;
use Tipoff\Locations\Models\Location;
use Tipoff\Payments\Enums\Gateway;
use Tipoff\Payments\Exceptions\PaymentChargeException;
use Tipoff\Payments\Objects\PaymentSettings;
use Tipoff\Support\Contracts\Payment\ChargeableInterface;

class StripePaymentGateway implements PaymentGateway
{
    public function getGatewayType(): Gateway
    {
        return Gateway::STRIPE();
    }

    public function charge(Location $location, ChargeableInterface $user, int $amount, array $options = []): string
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

            $payment = $user->charge($amount, $options['payment_method_id'], [
                'description' => $options['description'] ?? '',
            ]);

            return $payment->id;
        } catch (Exception $exception) {
            throw new PaymentChargeException($exception->getMessage());
        }
    }
}
