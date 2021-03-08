<?php

declare(strict_types=1);

namespace Tipoff\Payments\Services\PaymentGateway;

use Tipoff\Authorization\Models\User;
use Tipoff\Locations\Models\Location;

interface PaymentGateway
{
    public function charge(Location $location, User $user, int $amount, array $options = []): object;
}
