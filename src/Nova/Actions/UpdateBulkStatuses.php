<?php

namespace JustBetter\MagentoAsyncNova\Nova\Actions;

use Illuminate\Support\Collection;
use JustBetter\MagentoAsync\Jobs\UpdateBulkStatusesJob;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;

class UpdateBulkStatuses extends Action
{
    public function __construct()
    {
        $this
            ->withName(__('Update bulk statuses'))
            ->standalone();
    }

    public function handle(ActionFields $fields, Collection $models): ActionResponse
    {
        UpdateBulkStatusesJob::dispatch();

        return ActionResponse::message(__('Updating...'));
    }
}
