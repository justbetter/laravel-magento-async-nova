<?php

namespace JustBetter\MagentoAsyncNova\Nova\Metrics;

use JustBetter\MagentoAsync\Enums\OperationStatus;
use JustBetter\MagentoAsync\Models\BulkOperation;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Metrics\ValueResult;

class FailedCount extends Value
{
    public $icon = 'x';

    public $width = '1/4';

    public function calculate(NovaRequest $request): ValueResult
    {
        return $this->count($request, BulkOperation::query()->whereIn('status', [OperationStatus::RetriablyFailed, OperationStatus::NotRetriablyFailed]));
    }

    public function uriKey(): string
    {
        return 'bulk-operation-failed-count';
    }

    public function name(): string
    {
        return __('Failed');
    }
}
