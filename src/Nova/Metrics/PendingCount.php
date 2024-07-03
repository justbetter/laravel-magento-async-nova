<?php

namespace JustBetter\MagentoAsyncNova\Nova\Metrics;

use JustBetter\MagentoAsync\Enums\OperationStatus;
use JustBetter\MagentoAsync\Models\BulkOperation;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Metrics\ValueResult;

class PendingCount extends Value
{
    public $icon = 'clock';

    public $width = '1/4';

    public function calculate(NovaRequest $request): ValueResult
    {
        return $this->count($request, BulkOperation::query()->where('status', '=', OperationStatus::Open)->orWhereNull('status'));
    }

    public function uriKey(): string
    {
        return 'bulk-operation-pending-count';
    }

    public function name(): string
    {
        return __('Pending');
    }
}
