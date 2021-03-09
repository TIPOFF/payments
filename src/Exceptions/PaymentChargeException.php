<?php

declare(strict_types=1);

namespace Tipoff\Payments\Exceptions;

use Exception;
use Illuminate\Support\MessageBag;

class PaymentChargeException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }

    public function render()
    {
        $messages = new MessageBag;

        $messages->add('payment_charge', 'Payment method failed.');

        return redirect()->back()->withErrors($messages);
    }
}
