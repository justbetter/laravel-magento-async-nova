<?php

namespace JustBetter\MagentoAsyncNova\Nova\Actions;

use Illuminate\Support\Collection;
use JustBetter\MagentoAsync\Jobs\UpdateBulkStatusJob;
use JustBetter\MagentoAsync\Models\BulkRequest;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;

class UpdateBulkStatus extends Action
{
    public function __construct()
    {
        $this->withName(__('Update bulk status'));
    }

    /** @param Collection<BulkRequest> $models */
    public function handle(ActionFields $fields, Collection $models): ActionResponse
    {
        $models->each(function (BulkRequest $bulkRequest): void {
            UpdateBulkStatusJob::dispatch($bulkRequest);
        });

        return ActionResponse::message(__('Updating...'));
    }
}
