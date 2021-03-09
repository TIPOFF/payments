<?php

declare(strict_types=1);

namespace Tipoff\Payments\Models;

use Tipoff\Locations\Models\Location;
use Tipoff\Support\Models\BaseModel;
use Tipoff\Support\Traits\HasCreator;
use Tipoff\Support\Traits\HasPackageFactory;
use Tipoff\Support\Traits\HasUpdater;

class LocationPaymentSetting extends BaseModel
{
    use HasPackageFactory;
    use HasCreator;
    use HasUpdater;

    public static function forLocation(Location $location): ?self
    {
        return static::query()->where('location_id', '=', $location->id)->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
