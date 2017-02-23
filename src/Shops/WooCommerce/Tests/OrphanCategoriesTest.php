<?php

namespace Jtl\Connector\Integrity\Shops\WooCommerce\Tests;

use Jtl\Connector\Integrity\Models\Test\Error;

class OrphanCategoriesTest extends AbstractWooCommerceTest
{
    public function run()
    {
        global $wpdb;

        $orphans = $wpdb->get_col("
            SELECT t.term_id
            FROM `$wpdb->terms` t
            LEFT JOIN `$wpdb->term_taxonomy` tt ON t.term_id = tt.term_id
            WHERE tt.taxonomy = 'product_cat' AND tt.parent != 0 AND tt.parent NOT IN
            (
                SELECT term_id 
                FROM `$wpdb->terms`
            )
            ORDER BY t.term_id
        ");

        $result = $this->createResult(
            'Kategorien mit gelöschter Oberkategorie',
            'Kategorien verweisen auf eine Oberkategorie, die nicht mehr existiert.'
        );

        if (!empty($orphans)) {
            $this->addErrorToResult($result,
                'Die Oberkategorien von folgenden Kategorien existieren nicht: ' . implode(', ', $orphans),
                'Ordnen Sie den Kategorien eine neue Oberkategorie zu oder machen Sie die Kategorien zu Root Kategorien.',
                Error::LEVEL_ERROR
            );
        }

        $this->results->add($result);
    }
}
