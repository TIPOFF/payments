<?php

declare(strict_types=1);

namespace Tipoff\Payments\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Tipoff\Support\Nova\BaseResource;

class Payment extends BaseResource
{
    public static $model = \Tipoff\Payments\Models\Payment::class;

    public static $title = 'id';

    public static $search = [
        'id',
    ];

    public static function indexQuery(NovaRequest $request, $query)
    {
        if ($request->user()->hasRole([
            'Admin',
            'Owner',
            'Accountant',
            'Executive',
            'Reservation Manager',
            'Reservationist',
        ])) {
            return $query;
        }

        return $query->whereHas('order', function ($orderlocation) use ($request) {
            return $orderlocation->whereIn('location_id', $request->user()->locations->pluck('id'));
        });
    }

    public static $group = 'Operations';

    /** @psalm-suppress UndefinedClass */
    protected array $filterClassList = [

    ];

    public function fieldsForIndex(NovaRequest $request)
    {
        return array_filter([
            ID::make()->sortable(),
            nova('order') ? BelongsTo::make('Order', 'order', nova('order'))->sortable() : null,
            nova('customer') ? BelongsTo::make('Customer', 'customer', nova('customer'))->sortable() : null,
            Currency::make('Amount')->asMinorUnits()->sortable(),
            Currency::make('Amount refunded')->asMinorUnits()->sortable()->nullable(),
            Date::make('Created', 'created_at')->sortable(),
        ]);
    }

    public function fields(Request $request)
    {
        return array_filter([
            nova('order') ? BelongsTo::make('Order', 'order', nova('order'))->exceptOnForms() : null,
            nova('order') ? BelongsTo::make('Customer', 'customer', nova('customer'))->searchable()->withSubtitles()->exceptOnForms() : null,
            Currency::make('Amount')->asMinorUnits()->exceptOnForms(),
            Currency::make('Amount refunded')->asMinorUnits()->nullable()->exceptOnForms(),
            Text::make('Method')->exceptOnForms(),
            nova('invoice') ? BelongsTo::make('Invoice', 'invoice', nova('invoice'))->exceptOnForms() : null,

            nova('refund') ? HasMany::make('Refunds', 'refunds', nova('refund'))->exceptOnForms() : null,

            new Panel('Data Fields', $this->dataFields()),
        ]);
    }

    protected function dataFields(): array
    {
        return array_merge(
            parent::dataFields(),
            $this->creatorDataFields(),
            $this->updaterDataFields(),
        );
    }
}
