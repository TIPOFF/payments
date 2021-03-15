<?php

declare(strict_types=1);

namespace Tipoff\Payments\Models;

use Assert\Assert;
use Carbon\Carbon;
use Tipoff\Addresses\Traits\HasAddresses;
use Tipoff\Authorization\Models\User;
use Tipoff\Locations\Models\Location;
use Tipoff\Payments\Enums\Gateway;
use Tipoff\Payments\Enums\PaymentSource;
use Tipoff\Payments\Exceptions\RefundNotAvailableException;
use Tipoff\Payments\Services\CreatePayment;
use Tipoff\Support\Casts\Enum;
use Tipoff\Support\Contracts\Checkout\OrderInterface;
use Tipoff\Support\Contracts\Payment\PaymentInterface;
use Tipoff\Support\Contracts\Payment\RefundInterface;
use Tipoff\Support\Models\BaseModel;
use Tipoff\Support\Traits\HasCreator;
use Tipoff\Support\Traits\HasPackageFactory;
use Tipoff\Support\Traits\HasUpdater;

/**
 * @property int id
 * @property User user
 * @property Location location
 * @property OrderInterface order
 * @property int amount
 * @property int amount_refunded
 * @property int amount_refundable
 * @property string charge_number
 * @property Gateway gateway
 * @property PaymentSource source
 * @property User creator
 * @property User updater
 * @property Carbon created_at
 * @property Carbon updated_at
 * // Raw relations
 * @property int order_id
 * @property int location_id
 * @property int user_id
 * @property int creator_id
 * @property int updater_id
 */
class Payment extends BaseModel implements PaymentInterface
{
    use HasPackageFactory;
    use HasCreator;
    use HasUpdater;
    use HasAddresses;

    protected $casts = [
        'amount' => 'integer',
        'gateway' => Enum::class.':'.Gateway::class,
        'source' => Enum::class.':'.PaymentSource::class,
        'order_id' => 'integer',
        'user_id' => 'integer',
        'creator_id' => 'integer',
        'updater_id' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function (Payment $payment) {
            Assert::lazy()
                ->that($payment->order_id)->notEmpty('A payment must be applied to an order.')
                ->that($payment->user_id)->notEmpty('A payment must be made by a user.')
                ->verifyNow();

            $payment->amount_refunded = $payment->amount_refunded ?? 0;
        });
    }

    public function order()
    {
        return $this->belongsTo(app('order'));
    }

    public function location()
    {
        return $this->belongsTo(app('location'));
    }

    public function user()
    {
        return $this->belongsTo(app('user'));
    }

    public function invoice()
    {
        return $this->belongsTo(app('invoice'));
    }

    public function refunds()
    {
        return $this->hasMany(app('refund'));
    }

    public function getAmountRefundableAttribute()
    {
        return $this->amount - $this->amount_refunded;
    }

    public static function createPayment(int $locationId, $chargeable, int $amount, $paymentMethod, string $source): self
    {
        return app(CreatePayment::class)($locationId, $chargeable, $amount, $paymentMethod, $source);
    }

    public function attachOrder(OrderInterface $order): self
    {
        $this->order()->associate($order)->save();

        return $this;
    }

    public function requestRefund(int $amount, string $refundMethod): RefundInterface
    {
        /** @var RefundInterface $service */
        $service = findService(RefundInterface::class);
        throw_unless($service, RefundNotAvailableException::class);

        return $service::createRefund($this, $amount, $refundMethod);
    }

    public function getOrder(): OrderInterface
    {
        return $this->order;
    }

    public function getLocationId(): int
    {
        return $this->location_id;
    }
}
