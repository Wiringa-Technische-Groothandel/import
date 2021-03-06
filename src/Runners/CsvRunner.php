<?php

namespace WTG\Import\Runners;

use Luna\Importer\Contracts\Runner;
use Luna\Importer\Runners\CsvRunner as LunaCsvRunner;
use Webpatser\Uuid\Uuid;

/**
 * Csv runner.
 *
 * @package     WTG\Import
 * @subpackage  Runners
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class CsvRunner extends LunaCsvRunner implements Runner
{
    /**
     * Handle a csv line
     *
     * @param  array  $csvLine
     * @return void
     */
    public function handleLine(array $csvLine)
    {
        $fields = $this->importer->parseLine($csvLine);
        $hash = $this->makeHash($fields);
        $item = $this->importer->getModelInstance()
            ->where($this->importer->getUniqueKey(), $fields[$this->importer->getUniqueKey()])
            ->lockForUpdate()
            ->first();

        if ($item === null) {
            // Create a new item and fill it with the fields
            $item = $this->importer->getModelInstance();
            $item->fill($fields);
            $item->setId(Uuid::generate(4));

            $this->added++;
        } elseif ($hash !== $item->hash) {
            // Update the fields if there is a hash mismatch
            $item->fill($fields);

            $this->updated++;
        } elseif ($hash === $item->hash) {
            $this->unchanged++;
        }

        $item->hash = $hash;
        $item->imported_at = $this->now;

        if (!$this->dryRun) {
            $item->save();
        }
    }

    /**
     * Remove the products that have not been updated
     *
     * @return void
     */
    public function removeStale()
    {
        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = $this->importer->getModelInstance()
            ->where('imported_at', '<', $this->now)
            ->orWhereNull('imported_at')
            ->orWhereNull('hash');

        $this->deleted = $query->count();

        $query->delete();
    }
}