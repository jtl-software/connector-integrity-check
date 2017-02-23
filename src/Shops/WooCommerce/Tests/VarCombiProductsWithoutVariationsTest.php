<?php

namespace Jtl\Connector\Integrity\Shops\WooCommerce\Tests;

use Jtl\Connector\Integrity\Models\Test\Error;

class VarCombiProductsWithoutVariationsTest extends AbstractWooCommerceTest
{
    public function run()
    {
        $this->parentsWithoutVariations();
        $this->childrenWithoutVariations();
    }

    private function parentsWithoutVariations()
    {
        global $wpdb;

        $parents = $wpdb->get_results("
            SELECT p.ID, pm.meta_value
            FROM `$wpdb->posts` p
            LEFT JOIN `$wpdb->postmeta` pm ON p.ID = pm.post_id
            LEFT JOIN `$wpdb->term_relationships` tr ON tr.object_id = p.ID
            LEFT JOIN `$wpdb->term_taxonomy` tt ON tt.term_taxonomy_id = tr.term_taxonomy_id
            LEFT JOIN `$wpdb->terms` t ON t.term_id = tt.term_id
            WHERE p.post_type = 'product' AND tt.taxonomy = 'product_type' AND t.name = 'variable' AND pm.meta_key = '_product_attributes'
            ORDER BY p.ID
        ");

        $result = $this->createResult(
            'Vaterartikel ohne Variationen',
            'Vaterartikel müssen Variationen haben aus denen die Kinder erstellt wurden.'
        );

        if (!empty($parents)) {
            $ids = [];

            foreach ($parents as $parent) {
                if (empty(unserialize($parent->meta_value))) {
                    $ids[] = $parent->ID;
                }
            }

            if (!empty($ids)) {
                $this->addErrorToResult(
                    $result,
                    'Folgende Vaterartikel haben keine Variationen: ' . $ids,
                    'Überprüfen Sie, ob es sich nicht mehr um einen Varkombiartikel handelt.',
                    Error::LEVEL_ERROR
                );
            }
        }

        $this->results->add($result);
    }

    private function childrenWithoutVariations()
    {
        global $wpdb;

        $children = $wpdb->get_col("
            SELECT ID
            FROM `$wpdb->posts`
            WHERE post_type = 'product_variation' AND ID NOT IN
            (
                SELECT post_id
                FROM `$wpdb->postmeta`
                WHERE meta_key LIKE 'attribute_%%'
            )
            ORDER BY ID
        ");

        $result = $this->createResult(
            'Kindartikel ohne Variationswerte',
            'Kindartikel müssen Variationswerte haben.'
        );

        if (!empty($children)) {
            $this->addErrorToResult(
                $result,
                'Folgende Kindartikel haben keine Variationswerte: ' . implode(', ', $children),
                'Überprüfen Sie den Kind- sowie den Vaterartikel.',
                Error::LEVEL_ERROR
            );
        }

        $this->results->add($result);
    }
}
