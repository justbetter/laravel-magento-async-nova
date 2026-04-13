<?php

declare(strict_types=1);

namespace JustBetter\MagentoAsyncNova\Nova\Metrics;

use JustBetter\MagentoAsync\Enums\OperationStatus;
use JustBetter\MagentoAsync\Models\BulkOperation;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Metrics\ValueResult;

class RejectedCount extends Value
{
    public $icon = 'trash';

    public $width = '1/4';

    public function calculate(NovaRequest $request): ValueResult
    {
        return $this->count($request, BulkOperation::query()->where('status', '=', OperationStatus::Rejected));
    }

    #[\Override]
    public function uriKey(): string
    {
        return 'bulk-operation-rejected-count';
    }

    #[\Override]
    public function name(): string
    {
        return __('Rejected');
    }
}
