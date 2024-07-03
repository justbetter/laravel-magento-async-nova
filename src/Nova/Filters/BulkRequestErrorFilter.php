<?php

namespace JustBetter\MagentoAsyncNova\Nova\Filters;

use Illuminate\Database\Eloquent\Builder;
use JustBetter\MagentoAsync\Enums\OperationStatus;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class BulkRequestErrorFilter extends Filter
{
    public $name = 'Has Errors';

    public $component = 'select-filter';

    public function apply(NovaRequest $request, $query, $value): Builder
    {
        return $query->whereHas('operations', function (Builder $query) {
            $query->where('status', '!=', OperationStatus::Complete);
        });
    }

    public function options(NovaRequest $request): array
    {
        return [
            'Yes' => 'yes',
        ];
    }
}
