<?php

namespace Tipoff\Payments;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Tipoff\Payments\Payments
 */
class PaymentsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'payments';
    }
}
