<?php

namespace WTG\Import\Importers;

use Luna\Importer\Contracts\Importer;
use Illuminate\Database\Eloquent\Model;
use Luna\Importer\Importers\BaseImporter as LunaBaseImporter;

/**
 * Base importer.
 *
 * @package     WTG\Import
 * @subpackage  Importers
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
abstract class BaseImporter extends LunaBaseImporter implements Importer
{
    /**
     * Get a new instance of the model class.
     *
     * @return Model
     */
    public function getModelInstance(): Model
    {
        return app()->make($this->getModel());
    }

    /**
     * Get the import type.
     *
     * @return string
     */
    abstract public function getType(): string;
}