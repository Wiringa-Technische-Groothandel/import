<?php

namespace WTG\Import\Listeners;

use Carbon\Carbon;
use Luna\Importer\Events\ImportSuccess;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use WTG\Content\Interfaces\ContentInterface as Content;

/**
 * Import success listener.
 *
 * @package     WTG\Import
 * @subpackage  Listeners
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class ImportSuccessListener
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
     * @param  ImportSuccess  $event
     * @param  Content  $content
     * @return void
     */
    public function handle(ImportSuccess $event, Content $content)
    {
        $runner = $event->runner;
        $importer = $event->importer;

        $message = trans('import::message.success', [
            'count' => app('format')->number($runner->lines),
            'type' => __(str_plural($importer->getType(), $runner->lines)),
            'time' => Carbon::now()->diffInSeconds($runner->now),
            'memory' => app('format')->bytes(memory_get_peak_usage()),
            'added' => $runner->added,
            'updated' => $runner->updated,
            'deleted' => $runner->deleted,
            'unchanged' => $runner->unchanged
        ]);

        $content->tag('admin.' . $importer->getType() . '_import')->update([
            'content'    => $message,
            'updated_at' => Carbon::now('Europe/Amsterdam'),
            'error'      => false,
        ]);
    }
}