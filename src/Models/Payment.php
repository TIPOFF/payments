<?php

declare(strict_types=1);

namespace Tipoff\Payments\Models;

use Illuminate\Database\Eloquent\Model;
use Tipoff\Support\Models\BaseModel;
use Tipoff\Support\Traits\HasCreator;
use Tipoff\Support\Traits\HasPackageFactory;
use Tipoff\Support\Traits\HasUpdater;

class Payment extends BaseModel
{
    use HasPackageFactory;
    use HasCreator;
    use HasUpdater;

    const METHOD_STRIPE = 'Stripe';

    protected $casts = [
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($payment) {
            if (empty($payment->order_id)) {
                throw new \Exception('A payment must be applied to an order.');
            }
            if (empty($payment->user_id)) {
                throw new \Exception('A payment must be made by a user.');
            }
        });
    }

    public function order()
    {
        return $this->belongsTo(app('order'));
    }

    public function customer()
    {
        return $this->belongsTo(app('customer'));
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

    /**
     * Generate amount_refunded field.
     *
     * @return $this
     */
    public function generateAmountRefunded()
    {
        $this->amount_refunded = $this->refunds()->whereNotNull('issued_at')->get()->sum('amount');

        return $this;
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
