<?php

declare(strict_types=1);

namespace JustBetter\MagentoAsyncNova\Nova\Filters;

use Illuminate\Contracts\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Builder;
use JustBetter\MagentoAsync\Enums\OperationStatus;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class BulkOperationErrorFilter extends Filter
{
    public $name = 'Has Errors';

    public $component = 'select-filter';

    public function apply(NovaRequest $request, EloquentBuilder $query, mixed $value): Builder|EloquentBuilder
    {
        return $query->whereIn('status', OperationStatus::failedStatuses());
    }

    #[\Override]
    public function options(NovaRequest $request): array
    {
        return [
            'Yes' => 'yes',
        ];
    }
}
