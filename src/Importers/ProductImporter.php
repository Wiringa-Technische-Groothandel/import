<?php

namespace WTG\Import\Importers;

use Luna\Importer\Contracts\Importer;
use WTG\Catalog\Interfaces\ProductInterface as Product;

/**
 * Class ProductImporter
 *
 * @package     WTG\Import
 * @subpackage  Importers
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class ProductImporter extends BaseImporter implements Importer
{
    /**
     * @inheritdoc
     */
    public function getFilePath(): string
    {
        return storage_path('import/products.csv');
    }

    /**
     * @inheritdoc
     */
    public function getModel(): string
    {
        return Product::class;
    }

    /**
     * @inheritdoc
     */
    public function parseLine(array $data): array
    {
        return [
            'name'             => $data[0],
            'sku'              => $data[3],
            'group'            => $data[4],
            'alternate_sku'    => $data[5],
            'stock_code'       => $data[7],
            'registered_per'   => $data[8],
            'packed_per'       => $data[9],
            'price_per'        => $data[10],
            'refactor'         => preg_replace("/\,/", '.', $data[12]),
            'ean'              => $data[14],
            'image'            => $data[15],
            'length'           => $data[17],
            'price'            => preg_replace("/\,/", '.', $data[18]),
            'brand'            => $data[21],
            'series'           => $data[22],
            'type'             => $data[23],
            'special_price'    => ($data[24] === '' ? '0.00' : preg_replace("/\,/", '.', $data[24])),
            'action_type'      => $data[25],
            'keywords'         => $data[26],
            'related_products' => $data[27],
            'catalog_group'    => $data[28],
            'catalog_index'    => $data[29],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getType(): string
    {
        return 'product';
    }

    /**
     * @inheritdoc
     */
    public function validateLine(array $data): bool
    {
        return count($data) === 30;
    }

    /**
     * @inheritdoc
     */
    public function importSuccess()
    {
//        \Artisan::call('check:related_products');
    }

    /**
     * @inheritdoc
     */
    public function getUniqueKey(): string
    {
        return 'sku';
    }

    /**
     * @inheritdoc
     */
    public function shouldCleanup(): bool
    {
        return false;
    }
}