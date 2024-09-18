<?php

namespace JustBetter\MagentoAsyncNova\Nova;

use Illuminate\Http\Request;
use JustBetter\MagentoAsync\Enums\OperationStatus;
use JustBetter\MagentoAsync\Models\BulkOperation;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;

class BulkOperationResource extends Resource
{
    public static string $model = BulkOperation::class;

    public static $title = 'path';

    public static $group = 'Magento Async';

    public static $search = [
        'subject_type',
        'subject_id',
    ];

    public static $globallySearchable = false;

    public static function label(): string
    {
        return __('Operations');
    }

    public static function uriKey(): string
    {
        return 'magento-async-bulk-operations';
    }

    public function fields(NovaRequest $request): array
    {
        return [
            BelongsTo::make(__('Request'), 'request', BulkRequestResource::class),

            Number::make(__('Operation #'), 'operation_id')
                ->sortable(),

            Badge::make(__('Status'), 'status')
                ->map([
                    OperationStatus::Complete->value => 'success',
                    OperationStatus::RetriablyFailed->value => 'danger',
                    OperationStatus::NotRetriablyFailed->value => 'danger',
                    OperationStatus::Open->value => 'warning',
                    OperationStatus::Rejected->value => 'danger',
                    null => 'info',
                ])
                ->labels([
                    OperationStatus::Complete->value => 'Finished',
                    OperationStatus::RetriablyFailed->value => 'Failed with retries',
                    OperationStatus::NotRetriablyFailed->value => 'Failed without retries',
                    OperationStatus::Open->value => 'Pending',
                    OperationStatus::Rejected->value => 'Rejected',
                    null => 'Unknown',
                ]),

            MorphTo::make(__('Subject'), 'subject'),

            Text::make(__('Error Code'), 'response->error_code')
                ->sortable(),

            Text::make(__('Result Message'), 'response->result_message')
                ->sortable(),

            Code::make(__('Response'), 'response')
                ->json(),

            DateTime::make(__('Created At'), 'created_at')
                ->hideFromIndex(),

            DateTime::make(__('Updated At'), 'updated_at')
                ->hideFromIndex(),
        ];
    }

    public function cards(NovaRequest $request): array
    {
        return [
            Metrics\PendingCount::make(),
            Metrics\FinishedCount::make(),
            Metrics\FailedCount::make(),
            Metrics\RejectedCount::make(),
        ];
    }

    public function actions(NovaRequest $request): array
    {
        return [
            Actions\RetryBulkRequest::make(),
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
