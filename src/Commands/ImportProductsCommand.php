<?php

namespace WTG\Import\Commands;

use Illuminate\Console\Command;
use Luna\Importer\ImporterFacade;

/**
 * Import products command.
 *
 * @package     WTG\Import
 * @subpackage  Commands
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class ImportProductsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the products table';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        ImporterFacade::importer('product')->run();
    }
}