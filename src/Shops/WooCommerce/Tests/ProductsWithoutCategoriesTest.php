<?php

namespace Jtl\Connector\Integrity\Shops\WooCommerce\Tests;

use Jtl\Connector\Integrity\Models\Test\Error;

class ProductsWithoutCategoriesTest extends AbstractWooCommerceTest
{
    public function run()
    {
        global $wpdb;

        $products = $wpdb->get_col("
            SELECT ID
            FROM `$wpdb->posts`
            WHERE post_type = 'product' AND ID NOT IN
            (
                SELECT tr.object_id
                FROM `$wpdb->term_relationships` tr
                LEFT JOIN `$wpdb->term_taxonomy` tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
                WHERE tt.taxonomy = 'product_cat'
            )
            ORDER BY ID"
        );

        $result = $this->createResult(
            'Produkte ohne Kategorien',
            'JTL-Wawi zeigt Produkte ohne Kategorie nur an, wenn nach diesen gesucht wird.'
        );

        if (!empty($products)) {
            $this->addErrorToResult(
                $result,
                'Folgende Artikel haben keine Kategorie: ' . implode(', ', $products),
                'Ordnen Sie den Artikeln Kategorien zu.',
                Error::LEVEL_WARNING
            );
        }

        $this->results->add($result);
    }
}
