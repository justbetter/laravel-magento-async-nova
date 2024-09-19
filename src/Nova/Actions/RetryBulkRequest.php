<?php

namespace JustBetter\MagentoAsyncNova\Nova\Actions;

use Illuminate\Support\Collection;
use JustBetter\MagentoAsync\Contracts\RetriesBulkRequest;
use JustBetter\MagentoAsync\Models\BulkOperation;
use JustBetter\MagentoAsync\Models\BulkRequest;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Http\Requests\NovaRequest;

class RetryBulkRequest extends Action
{
    public function __construct()
    {
        $this
            ->withName(__('Retry bulk requests'));
    }

    public function handle(ActionFields $fields, Collection $models): ActionResponse
    {
        /** @var RetriesBulkRequest $action */
        $action = app(RetriesBulkRequest::class);
        $onlyFailed = $fields->get('only_failed');

        /** @var BulkRequest|BulkOperation $model */
        foreach ($models as $model) {

            if ($model instanceof BulkRequest) {
                /** @var BulkRequest $model */
                $request = $model;
            } else {
                /** @var BulkOperation $model */
                $request = $model->request;
            }

            $action->retry($request, $onlyFailed);
        }

        return ActionResponse::message(__('Created retries'));
    }

    public function fields(NovaRequest $request): array
    {
        return [
            Boolean::make(__('Only Failed'), 'only_failed')
                ->default(true),
        ];
    }
}
