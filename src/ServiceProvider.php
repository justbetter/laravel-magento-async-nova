<?php

namespace JustBetter\MagentoASyncNova;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Laravel\Nova\Nova;

class ServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        $this
            ->registerConfig();
    }

    protected function registerConfig(): static
    {
        $this->mergeConfigFrom(__DIR__.'/../config/magento-async-nova.php', 'magento-async-nova');

        return $this;
    }

    public function boot(): void
    {
        $this
            ->bootConfig()
            ->bootResources();
    }

    protected function bootConfig(): static
    {
        $this->publishes([
            __DIR__.'/../config/magento-async-nova.php' => config_path('magento-async-nova.php'),
        ], 'config');

        return $this;
    }

    protected function bootResources(): static
    {
        Nova::serving(function (): void {
            Nova::resources([
                config('magento-async-nova.resources.bulk_request'),
                config('magento-async-nova.resources.bulk_operation'),
            ]);
        });

        return $this;
    }
}
