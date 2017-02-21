<?php

namespace Jtl\Connector\Integrity\Shops\WooCommerce\Tests;

use Jtl\Connector\Integrity\Models\Test\Data;
use Jtl\Connector\Integrity\Models\Test\Error;
use Jtl\Connector\Integrity\Models\Test\Result;
use Jtl\Connector\Integrity\Models\Test\TestType;

class VarCombiChildrenWithSimpleFather extends BaseTest
{
    public function run()
    {
        global $wpdb;

        $childProducts = $wpdb->get_results(sprintf("
            SELECT p.ID, p.post_parent 
            FROM `%s` p 
            WHERE p.post_type = 'product_variation' AND p.post_parent IN
            (
                SELECT p2.ID
                FROM `%s` p2
                LEFT JOIN `%s` tr ON tr.object_id = p2.ID
                LEFT JOIN `%s` tt ON tt.term_taxonomy_id = tr.term_taxonomy_id
                LEFT JOIN `%s` t ON t.term_id = tt.term_id
                WHERE p2.post_type = 'product' AND tt.taxonomy = 'product_type' AND t.name = 'simple'
            )",
            $wpdb->posts, $wpdb->posts, $wpdb->term_relationships, $wpdb->term_taxonomy, $wpdb->terms
        ));

        $result = new Result($this->sort);
        $result->setName('Kindartikel mit "einfachem" Vaterartikel');
        $result->setDescription('Kindartikel verweisen auf einen Vaterartikelm, der mittlerweile nur noch ein "einfacher" Artikel ist.');

        if (!empty($childProducts)) {
            $messages = [];
            $parentChildren = [];

            foreach ($childProducts as $child) {
                $parentChildren[$child->post_parent][] = $child->ID;
            }

            foreach ($parentChildren as $parent => $children) {
                $messages[] = sprintf(
                    'Einfacher Artikel "%d" hat noch folgende veraltete Kindartikel: %s',
                    $parent, implode(", ", $children)
                );
            }

            $error = new Error();
            $error->setMessage(implode('<br>', $messages));
            $error->setLevel(Error::LEVEL_CRITICAL);
            $error->setSolution('Löschen Sie die Kindartikel.');
            $result->setError($error);
        }

        $this->results->add($result);
    }
}