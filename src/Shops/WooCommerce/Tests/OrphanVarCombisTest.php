<?php

namespace Jtl\Connector\Integrity\Shops\WooCommerce\Tests;

use Jtl\Connector\Integrity\Models\Test\Error;

class OrphanVarCombisTest extends AbstractWooCommerceTest
{
    public function run()
    {
        $this->parentsWithoutChildren();
        $this->childrenWithoutParents();
    }

    private function parentsWithoutChildren()
    {
        global $wpdb;

        $parents = $wpdb->get_col("
            SELECT p.ID
            FROM `$wpdb->posts` p
            LEFT JOIN `$wpdb->term_relationships` tr ON tr.object_id = p.ID
            LEFT JOIN `$wpdb->term_taxonomy` tt ON tt.term_taxonomy_id = tr.term_taxonomy_id
            LEFT JOIN `$wpdb->terms` t ON t.term_id = tt.term_id
            WHERE p.post_type = 'product' AND tt.taxonomy = 'product_type' AND t.name = 'variable' AND p.ID NOT IN
            (
                SELECT post_parent
                FROM `$wpdb->posts`
                WHERE post_type = 'product_variation'
            )
            ORDER BY p.ID"
        );

        $result = $this->createResult(
            'Vaterartikel ohne Kindartikel',
            'Vaterartikel müssen Kindartikel haben.'
        );

        if (!empty($parents)) {
            $this->addErrorToResult(
                $result,
                'Folgende Vaterartikel haben keine Kindartikel: ' . implode(', ', $parents),
                'Löschen Sie den Artikel oder erstellen neue Kindartikel.',
                Error::LEVEL_CRITICAL
            );
        }

        $this->results->add($result);
    }

    private function childrenWithoutParents()
    {
        global $wpdb;

        $children = $wpdb->get_col("
            SELECT ID
            FROM `$wpdb->posts`
            WHERE post_type = 'product_variation' AND post_parent NOT IN
            (
                SELECT ID
                FROM `$wpdb->posts`
                WHERE post_type = 'product'
            )
            ORDER BY ID"
        );

        $result = $this->createResult(
            'Kindartikel ohne Vaterartikel',
            'Kindartikel verweisen auf einen Vaterartikel, der nicht mehr existiert.'
        );

        if (!empty($children)) {
            $this->addErrorToResult(
                $result,
                'Folgende Kindartikel haben keinen Vater: ' . implode(', ', $children),
                'Löschen Sie die Kindartikel.',
                Error::LEVEL_CRITICAL
            );
        }

        $this->results->add($result);
    }
}
