<?php

namespace WTG\Import\Providers;

use Luna\Importer\ImporterFacade;
use Illuminate\Support\ServiceProvider;
use WTG\Import\Commands\ImportProductsCommand;
use WTG\Import\Commands\ImportDiscountsCommand;
use Luna\Importer\ServiceProvider as ImporterServiceProvider;

/**
 * Import service provider
 *
 * @package     WTG\Import
 * @subpackage  Providers
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class ImportServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__.'/../config.php', 'importer');

        if ($this->app->runningInConsole()) {
            $this->commands([
                ImportProductsCommand::class,
                ImportDiscountsCommand::class,
            ]);
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(ImporterServiceProvider::class);
        $this->app->alias("Import", ImporterFacade::class);
    }
}
