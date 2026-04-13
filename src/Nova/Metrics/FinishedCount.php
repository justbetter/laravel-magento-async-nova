<?php

declare(strict_types=1);

namespace JustBetter\MagentoAsyncNova\Nova\Metrics;

use JustBetter\MagentoAsync\Enums\OperationStatus;
use JustBetter\MagentoAsync\Models\BulkOperation;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Metrics\ValueResult;

class FinishedCount extends Value
{
    public $icon = 'check';

    public $width = '1/4';

    public function calculate(NovaRequest $request): ValueResult
    {
        return $this->count($request, BulkOperation::query()->where('status', '=', OperationStatus::Complete));
    }

    #[\Override]
    public function uriKey(): string
    {
        return 'bulk-operation-finished-count';
    }

    #[\Override]
    public function name(): string
    {
        return __('Finished');
    }
}
