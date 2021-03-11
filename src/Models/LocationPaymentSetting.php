<?php

declare(strict_types=1);

namespace Tipoff\Payments\Models;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Tipoff\Authorization\Models\User;
use Tipoff\Locations\Models\Location;
use Tipoff\Support\Models\BaseModel;
use Tipoff\Support\Traits\HasCreator;
use Tipoff\Support\Traits\HasPackageFactory;
use Tipoff\Support\Traits\HasUpdater;

/**
 * @property int id
 * @property Location location
 * @property string stripe_publishable
 * @property string stripe_secret
 * @property User creator
 * @property User updater
 * @property Carbon created_at
 * @property Carbon updated_at
 * // Raw relations
 * @property int location_id
 * @property int creator_id
 * @property int updater_id
 */
class LocationPaymentSetting extends BaseModel
{
    use HasPackageFactory;
    use HasCreator;
    use HasUpdater;

    public static function forLocation(Location $location): ?self
    {
        /** @var LocationPaymentSetting $result */
        $result = static::query()->where('location_id', '=', $location->id)->first();

        return $result;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function getStripePublishableAttribute($value)
    {
        return $value ?? $this->getStripeConfigKey('publishable');
    }

    public function getStripeSecretAttribute($value)
    {
        return $value ?? $this->getStripeConfigKey('secret');
    }

    private function getStripeConfigKey(string $type): ?string
    {
        $keys = config('payments.stripe_keys');

        $keySet = Arr::get($keys, "locations.{$this->location->abbreviation}", $keys['default'] ?? []);

        return $keySet[$type] ?? null;
    }

    public static function stripeEnvKeyPair(?string $envPrefix = null): array
    {
        $keyPrefix = strtoupper($envPrefix ?? '');
        return [
            'publishable' => env("{$keyPrefix}STRIPE_PUBLISHABLE_KEY"),
            'secret' => env("{$keyPrefix}STRIPE_SECRET_KEY"),
        ];
    }
}
