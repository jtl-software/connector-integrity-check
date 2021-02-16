<?php

namespace Jtl\Connector\Integrity\Shops\WooCommerce\Tests;

use Jtl\Connector\Integrity\Models\Test\Error;

class NotSupportedProductTypesTest extends AbstractWooCommerceTest
{
    public function run()
    {
        $this->requireConfigFile();
        global $wpdb;

        $products = $wpdb->get_col("
            SELECT p.ID
            FROM `$wpdb->posts` p
            LEFT JOIN `$wpdb->term_relationships` tr ON p.ID = tr.object_id
            LEFT JOIN `$wpdb->term_taxonomy` tt ON tt.term_taxonomy_id = tr.term_taxonomy_id
            LEFT JOIN `$wpdb->terms` t ON t.term_id = tt.term_id
            WHERE p.post_type IN ('product', 'product_variation') AND tt.taxonomy = 'product_type' AND t.slug NOT IN ('simple', 'variable', 'variation')
        ");

        $result = $this->createResult(
            'Nicht unterstützte Produkttypen',
            'Es werden nur einfache Artikel und Variationskombinationen unterstützt.'
        );

        if (!empty($products)) {
            $this->addErrorToResult($result,
                'Die folgenden Artikel werden nicht bertragen: ' . implode(', ', $products),
                '',
                Error::LEVEL_WARNING
            );
        }

        $this->results->add($result);
    }
}