<?php

namespace JustBetter\MagentoAsyncNova\Nova;

use Illuminate\Http\Request;
use JustBetter\MagentoAsync\Enums\OperationStatus;
use JustBetter\MagentoAsync\Models\BulkRequest;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Laravel\Nova\Resource;

class BulkRequestResource extends Resource
{
    public static string $model = BulkRequest::class;

    public static $title = 'bulk_uuid';

    public static $group = 'Magento Async';

    public static $search = [
        'store_code',
        'path',
        'bulk_uuid',
    ];

    public static $globallySearchable = false;

    public static $with = ['operations'];

    public static function label(): string
    {
        return __('Requests');
    }

    public static function uriKey(): string
    {
        return 'magento-async-bulk-requests';
    }

    public function fields(NovaRequest $request): array
    {
        return [
            Text::make(__('Store'), 'store_code')
                ->filterable()
                ->sortable(),

            Text::make(__('method'), 'method')
                ->filterable()
                ->sortable(),

            Text::make(__('Path'), 'path')
                ->sortable(),

            Text::make(__('UUID'), 'bulk_uuid')
                ->filterable()
                ->sortable(),

            Badge::make(__('Status'), function (BulkRequest $request) {
                $pendingCount = $request->operations
                    ->whereIn('status', [OperationStatus::Open, null])
                    ->count();

                if ($pendingCount > 0) {
                    return 'pending';
                }

                $failedCount = $request->operations()
                    ->whereIn('status', [
                        OperationStatus::RetriablyFailed,
                        OperationStatus::NotRetriablyFailed,
                        OperationStatus::Rejected,
                    ])
                    ->count();

                return $failedCount === 0
                    ? 'finished'
                    : 'finished_with_errors';
            })->map([
                'pending' => 'warning',
                'finished' => 'success',
                'finished_with_errors' => 'danger',
            ])->labels([
                'pending' => 'Pending',
                'finished' => 'Finished',
                'finished_with_errors' => 'Finished with errors',
            ]),

            Number::make(__('Pending'), function (BulkRequest $request): int {
                return $request->operations
                    ->whereIn('status', [OperationStatus::Open, null])
                    ->count();
            }),

            Number::make(__('Finished'), function (BulkRequest $request): int {
                return $request->operations
                    ->where('status', '=', OperationStatus::Complete)
                    ->whereNotNull('status')
                    ->count();
            }),

            Number::make(__('Failed'), function (BulkRequest $request): int {
                return $request->operations
                    ->whereIn('status', [OperationStatus::RetriablyFailed, OperationStatus::NotRetriablyFailed])
                    ->count();
            }),

            Number::make(__('Rejected'), function (BulkRequest $request): int {
                return $request->operations
                    ->where('status', '=', OperationStatus::Rejected)
                    ->count();
            }),

            DateTime::make(__('Started At'), 'started_at')
                ->filterable()
                ->sortable(),

            DateTime::make(__('Created At'), 'created_at')
                ->hideFromIndex(),

            DateTime::make(__('Updated At'), 'updated_at')
                ->hideFromIndex(),

            Panel::make(__('Data'), [
                Code::make(__('Request'), 'request')
                    ->json(),

                Code::make(__('Response'), 'response')
                    ->json(),
            ])->collapsable()->collapsedByDefault(),

            BelongsTo::make(__('Retry Of'), 'retryOf', static::class),

            HasMany::make(__('Operations'), 'operations', BulkOperationResource::class),

            HasMany::make(__('Retries'), 'retries', static::class),

        ];
    }

    public function actions(NovaRequest $request): array
    {
        return [
            Actions\RetryBulkRequest::make(),
            Actions\CleanBulkRequests::make(),
            Actions\UpdateBulkStatus::make(),
            Actions\UpdateBulkStatuses::make(),
        ];
    }

    public function filters(NovaRequest $request): array
    {
        return [
            Filters\BulkRequestErrorFilter::make(),
        ];
    }

    public static function authorizedToCreate(Request $request): bool
    {
        return false;
    }

    public function authorizedToUpdate(Request $request): bool
    {
        return false;
    }

    public function authorizedToReplicate(Request $request): bool
    {
        return false;
    }
}
