<?php namespace TipOff\Payments\Models;

use Tipoff\Support\Models\BaseModel;
use Tipoff\Support\Traits\HasPackageFactory;

class Payment extends BaseModel
{
    use HasPackageFactory;

    const METHOD_STRIPE = 'Stripe';

    protected $guarded = ['id'];
    protected $casts = [
    ];

    protected $refund = app('refund');

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (empty($payment->creator_id) && auth()->check()) {
                $payment->creator_id = auth()->id();
            }
        });

        static::saving(function ($payment) {
            if (empty($payment->order_id)) {
                throw new \Exception('A payment must be applied to an order.');
            }
            if (empty($payment->customer_id)) {
                throw new \Exception('A payment must be made by a customer.');
            }
            if (auth()->check()) {
                $payment->updater_id = auth()->id();
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

    public function creator()
    {
        return $this->belongsTo(app('user'), 'creator_id');
    }

    public function updater()
    {
        return $this->belongsTo(app('user'), 'updater_id');
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
        return $this->refund::create([
            'amount' => $amount,
            'method' => $method,
            'payment_id' => $this->id,
            'creator_id' => auth()->user()->id,
        ]);
    }
}
