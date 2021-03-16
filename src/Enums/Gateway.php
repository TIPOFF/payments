<?php

declare(strict_types=1);

namespace Tipoff\Payments\Enums;

use Tipoff\Support\Enums\BaseEnum;

/**
 * @method static Gateway STRIPE()
 * @psalm-immutable
 */
class Gateway extends BaseEnum
{
    const STRIPE = 'stripe';
}
