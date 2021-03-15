<?php

declare(strict_types=1);

namespace Tipoff\Payments\Enums;

use Tipoff\Support\Enums\BaseEnum;

/**
 * @method static PaymentSource ONLINE()
 * @method static PaymentSource PHONE()
 * @method static PaymentSource IN_PERSON()
 * @psalm-immutable
 */
class PaymentSource extends BaseEnum
{
    const ONLINE = 'online';
    const PHONE = 'phone';
    const IN_PERSON = 'in-person';
}
