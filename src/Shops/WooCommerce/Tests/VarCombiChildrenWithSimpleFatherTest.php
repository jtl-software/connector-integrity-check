<?php

namespace Jtl\Connector\Integrity\Shops\WooCommerce\Tests;

use Jtl\Connector\Integrity\Models\Test\Error;

class VarCombiChildrenWithSimpleFatherTest extends AbstractWooCommerceTest
{
    public function run()
    {
        global $wpdb;

        $childProducts = $wpdb->get_results("
            SELECT ID, post_parent 
            FROM `$wpdb->posts`
            WHERE post_type = 'product_variation' AND post_parent IN
            (
                SELECT p.ID
                FROM `$wpdb->posts` p
                LEFT JOIN `$wpdb->term_relationships` tr ON tr.object_id = p.ID
                LEFT JOIN `$wpdb->term_taxonomy` tt ON tt.term_taxonomy_id = tr.term_taxonomy_id
                LEFT JOIN `$wpdb->terms` t ON t.term_id = tt.term_id
                WHERE p.post_type = 'product' AND tt.taxonomy = 'product_type' AND t.name = 'simple'
            )
            ORDER BY ID"
        );

        $result = $this->createResult(
            'Kindartikel mit "einfachem" Vaterartikel',
            'Kindartikel verweisen auf einen Vaterartikelm, der mittlerweile nur noch ein "einfacher" Artikel ist.'
        );

        if (!empty($childProducts)) {
            $messages = [];
            $parentChildren = [];

            foreach ($childProducts as $child) {
                $parentChildren[$child->post_parent][] = $child->ID;
            }

            foreach ($parentChildren as $parent => $children) {
                $messages[] = "Einfacher Artikel ($parent) hat veraltete Kindartikel: " . implode(", ", $children);
            }

            $this->addErrorToResult($result,
                implode('<br>', $messages),
                'Löschen Sie die Kindartikel.',
                Error::LEVEL_CRITICAL
            );
        }

        $this->results->add($result);
    }
}
