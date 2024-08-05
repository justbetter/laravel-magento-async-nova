<?php

namespace JustBetter\MagentoAsyncNova\Nova\Actions;

use Illuminate\Support\Collection;
use JustBetter\MagentoAsync\Jobs\CleanBulkRequestsJob;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;

class CleanBulkRequests extends Action
{
    public function __construct()
    {
        $this
            ->withName(__('Clean bulk requests'))
            ->standalone();
    }

    public function handle(ActionFields $fields, Collection $models): ActionResponse
    {
        CleanBulkRequestsJob::dispatch();

        return ActionResponse::message(__('Cleaning...'));
    }
}
