<?php

namespace Jtl\Connector\Integrity\Shops\WooCommerce\Tests;

use Jtl\Connector\Integrity\Models\Test\Error;

class ProductsWithoutPriceTest extends AbstractWooCommerceTest
{

    public function run()
    {
        $this->requireConfigFile();
        global $wpdb;

        $products = $wpdb->get_col("
            SELECT post_id
            FROM `$wpdb->postmeta`
            WHERE meta_key = '_price' AND meta_value = '' OR meta_value IS NULL
            ORDER BY post_id
        ");

        $result = $this->createResult(
            'Produkte ohne Preis',
            'JTL-Wawi setzt den Produktpreis auf 0€ und fügt das Attribut payable mit dem Wert `false` hinzu.'
        );

        if (!empty($products)) {
            $this->addErrorToResult(
                $result,
                'Folgende Artikel haben keinen Preis: ' . implode(', ', $products),
                'Geben Sie den Artikeln einen Preis.',
                Error::LEVEL_WARNING
            );
        }

        $this->results->add($result);
    }
}