<?php

namespace WTG\Import\Listeners;

use Carbon\Carbon;
use Luna\Importer\Events\ImportFailed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use WTG\Content\Interfaces\ContentInterface as Content;

/**
 * Import failed listener.
 *
 * @package     WTG\Import
 * @subpackage  Listeners
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class ImportFailedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ImportFailed  $event
     * @param  Content  $content
     * @return void
     */
    public function handle(ImportFailed $event, Content $content)
    {
        $runner = $event->runner;
        $importer = $event->importer;
        $exception = $event->exception;

        $message = trans('import::message.error', [
            'type' => $importer->getType(),
            'error' => $exception->getMessage()
        ]);

        $content->tag('admin.' . $importer->getType() . '_import')->update([
            'content'    => $message,
            'updated_at' => Carbon::now('Europe/Amsterdam'),
            'error'      => true,
        ]);

        \Mail::send('email.import_error_notice', [
            'error' => $message,
            'type' => $importer->getType()
        ], function ($message) use ($importer) {
            $message->subject('[' . config('app.name') . '] ' . ucfirst($importer->getType()) . ' import error');
        });
    }
}