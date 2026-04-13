<?php

use JustBetter\MagentoAsyncNova\Nova\BulkOperationResource;
use JustBetter\MagentoAsyncNova\Nova\BulkRequestResource;

return [
    'resources' => [
        'bulk_request' => BulkRequestResource::class,
        'bulk_operation' => BulkOperationResource::class,
    ],
];
