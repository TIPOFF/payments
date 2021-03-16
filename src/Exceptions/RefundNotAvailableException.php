<?php

declare(strict_types=1);

namespace Tipoff\Payments\Exceptions;

use Throwable;

class RefundNotAvailableException extends \InvalidArgumentException implements PaymentException
{
    public function __construct($code = 0, Throwable $previous = null)
    {
        parent::__construct('Refund services are not enabled.', $code, $previous);
    }
}
