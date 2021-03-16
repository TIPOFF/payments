<?php

declare(strict_types=1);

namespace Tipoff\Payments\Services;

use Tipoff\Locations\Models\Location;
use Tipoff\Payments\Enums\PaymentSource;
use Tipoff\Payments\Models\Payment;
use Tipoff\Payments\Services\PaymentGateway\PaymentGateway;

class CreatePayment
{
    public function __invoke(int $locationId, $chargeable, int $amount, $paymentMethod, string $source): Payment
    {
        /** @var Location $location */
        $location = Location::findOrFail($locationId);

        $service = app(PaymentGateway::class);
        $chargeId = $service->charge($location, $chargeable, $amount, [
            'payment_method_id' => $paymentMethod,
        ]);

        $result = new Payment();
        $result->source = PaymentSource::byValue($source);
        $result->gateway = $service->getGatewayType();
        $result->charge_number = $chargeId;
        $result->amount = $amount;
        $result->location_id = $locationId;
        $result->user()->associate($chargeable);

        return $result;
    }
}
