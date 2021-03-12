<?php

declare(strict_types=1);

namespace Tipoff\Payments\Models;

use Assert\Assert;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Tipoff\Addresses\Traits\HasAddresses;
use Tipoff\Authorization\Models\User;
use Tipoff\Checkout\Models\Order;
use Tipoff\Support\Models\BaseModel;
use Tipoff\Support\Traits\HasCreator;
use Tipoff\Support\Traits\HasPackageFactory;
use Tipoff\Support\Traits\HasUpdater;

/**
 * @property int id
 * @property Order order
 * @property User user
 * @property int amount
 * @property int amount_refunded
 * @property string charge_id
 * @property User creator
 * @property User updater
 * @property Carbon created_at
 * @property Carbon updated_at
 * // Raw relations
 * @property int order_id
 * @property int user_id
 * @property int creator_id
 * @property int updater_id
 */
class Payment extends BaseModel
{
    use HasPackageFactory;
    use HasCreator;
    use HasUpdater;
    use HasAddresses;

    const METHOD_STRIPE = 'Stripe';

    protected $casts = [
        'amount' => 'integer',
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
        });
    }

    public function order()
    {
        return $this->belongsTo(app('order'));
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

    public function getAmountRefundedAttribute()
    {
        return $this->refunds()->whereNotNull('issued_at')->sum('amount');
    }

    public function getAmountRefundableAttribute()
    {
        return $this->amount - $this->amount_refunded;
    }

    /**
     * Refund payment request.
     *
     * @param int|null $amount Amount used in partial refunds.
     * @param string $method
     * @return mixed
     */
    public function requestRefund($amount = null, $method = 'Stripe')
    {
        /** @var Model $refundModel */
        $refundModel = app('refund');

        return $refundModel::create([
            'amount' => $amount,
            'method' => $method,
            'payment_id' => $this->id,
            'creator_id' => auth()->user()->id,
        ]);
    }
}
