<?php

namespace App\Nova;

use App\Nova\Actions\RequestRefund;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Payment extends Resource
{
    public static $model = \App\Models\Payment::class;

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

    public function fieldsForIndex(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make('Order')->sortable(),
            BelongsTo::make('Customer')->sortable(),
            Currency::make('Amount')->asMinorUnits()->sortable(),
            Currency::make('Amount refunded')->asMinorUnits()->sortable()->nullable(),
            Date::make('Created', 'created_at')->sortable(),
        ];
    }

    public function fields(Request $request)
    {
        return [
            BelongsTo::make('Order')->exceptOnForms(),
            BelongsTo::make('Customer')->searchable()->withSubtitles()->exceptOnForms(),
            Currency::make('Amount')->asMinorUnits()->exceptOnForms(),
            Currency::make('Amount refunded')->asMinorUnits()->nullable()->exceptOnForms(),
            Text::make('Method')->exceptOnForms(),
            BelongsTo::make('Invoice')->exceptOnForms(),

            HasMany::make('Refunds')->exceptOnForms(),

            new Panel('Data Fields', $this->dataFields()),
        ];
    }

    protected function dataFields()
    {
        return [
            ID::make(),
            BelongsTo::make('Creator', 'creator', \App\Nova\User::class)->exceptOnForms(),
            DateTime::make('Created At')->exceptOnForms(),
            BelongsTo::make('Updater', 'updater', \App\Nova\User::class)->exceptOnForms(),
            DateTime::make('Updated At')->exceptOnForms(),
        ];
    }

    public function cards(Request $request)
    {
        return [];
    }

    public function filters(Request $request)
    {
        return [
            new Filters\OrderLocation,
        ];
    }

    public function lenses(Request $request)
    {
        return [];
    }

    public function actions(Request $request)
    {
        return [
            new RequestRefund,
        ];
    }
}
