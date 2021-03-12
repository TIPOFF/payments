<?php

declare(strict_types=1);

namespace Tipoff\Payments\Objects;

use Illuminate\Support\Arr;
use Tipoff\Locations\Models\Location;

class PaymentSettings
{
    private const STRIPE_PUBLISHABLE = 'publishable';
    private const STRIPE_SECRET = 'secret';

    private array $stripeKeySet = [];

    public static function forLocation(Location $location): ?self
    {
        $result = new static;

        return $result->loadStripeKeySet($location->abbreviation);
    }

    public function getStripePublishable(): ?string
    {
        return $this->stripeKeySet[self::STRIPE_PUBLISHABLE] ?? null;
    }

    public function getStripeSecret(): ?string
    {
        return $this->stripeKeySet[self::STRIPE_SECRET] ?? null;
    }

    private function loadStripeKeySet(string $locationAbbreviation): self
    {
        $keys = config('payments.stripe_keys');

        $this->stripeKeySet = Arr::get($keys, "locations.{$locationAbbreviation}", $keys['default'] ?? []);

        return $this;
    }

    public static function stripeEnvKeyPair(?string $envPrefix = null): array
    {
        $keyPrefix = strtoupper($envPrefix ?? '');

        return [
            self::STRIPE_PUBLISHABLE => env("{$keyPrefix}STRIPE_PUBLISHABLE_KEY"),
            self::STRIPE_SECRET => env("{$keyPrefix}STRIPE_SECRET_KEY"),
        ];
    }
}
