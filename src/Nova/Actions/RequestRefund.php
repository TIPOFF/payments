<?php

declare(strict_types=1);

namespace Tipoff\Payments\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Select;
use Tipoff\Payments\Models\Payment;

class RequestRefund extends Action
{
    use InteractsWithQueue;
    use Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $models->each(function (Payment $model) use ($fields) {
            $model->requestRefund($fields->amount, $fields->type);
        });

        return Action::message($fields->type.' refund requested.');
    }

    public function fields()
    {
        return [
            Currency::make('Amount')->asMinorUnits()->required(),
            Select::make('Type')->options([
                'Voucher' => 'Voucher',
                'Stripe' => 'Stripe',
            ])->required(),
        ];
    }
}
