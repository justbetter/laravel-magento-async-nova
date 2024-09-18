<?php

namespace JustBetter\MagentoAsyncNova\Nova\Filters;

use Illuminate\Database\Eloquent\Builder;
use JustBetter\MagentoAsync\Enums\OperationStatus;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class BulkOperationErrorFilter extends Filter
{
    public $name = 'Has Errors';

    public $component = 'select-filter';

    public function apply(NovaRequest $request, $query, $value): Builder
    {
        return $query->whereIn('status', OperationStatus::failedStatuses());
    }

    public function options(NovaRequest $request): array
    {
        return [
            'Yes' => 'yes',
        ];
    }
}
