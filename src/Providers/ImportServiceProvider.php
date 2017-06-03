<?php

namespace WTG\Import\Providers;

use Luna\Importer\ImporterFacade;
use Luna\Importer\Events\ImportFailed;
use Luna\Importer\Events\ImportSuccess;
use Illuminate\Support\ServiceProvider;
use WTG\Import\Commands\ImportProductsCommand;
use WTG\Import\Listeners\ImportFailedListener;
use WTG\Import\Commands\ImportDiscountsCommand;
use WTG\Import\Listeners\ImportSuccessListener;
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
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        ImportSuccess::class => [
            ImportSuccessListener::class,
        ],
        ImportFailed::class => [
            ImportFailedListener::class,
        ],
    ];

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__.'/../config.php', 'importer');

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'import');

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
