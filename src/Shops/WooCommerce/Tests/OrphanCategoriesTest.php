<?php

namespace Jtl\Connector\Integrity\Shops\WooCommerce\Tests;

use Jtl\Connector\Integrity\Models\Test\Data;
use Jtl\Connector\Integrity\Models\Test\Error;
use Jtl\Connector\Integrity\Models\Test\Result;
use Jtl\Connector\Integrity\Models\Test\TestType;

class OrphanCategoriesTest extends BaseTest
{
    public function run()
    {
        global $wpdb;

        $orphans = $wpdb->get_results(sprintf("
            SELECT t.term_id, t.name, tt.parent
            FROM %s t
            LEFT JOIN %s tt ON t.term_id = tt.term_id
            WHERE tt.taxonomy = 'product_cat' AND tt.parent != 0 AND tt.parent NOT IN
            (
              SELECT t2.term_id FROM %s t2
            )",
            $wpdb->terms,
            $wpdb->term_taxonomy,
            $wpdb->terms
        ));

        $result = new Result($this->sort);
        $result->setName('Kategorien mit gelöschter Oberkategorie');
        $result->setDescription('Oberkategorie zu einer Kategorie muss existieren.');

        if (!empty($orphans)) {
            foreach ($orphans as $orphan) {
                $error = new Error();
                $error->setMessage(sprintf(
                    'Die Oberkategorie (%d) von "%s" (%d) existiert nicht',
                    $orphan->parent, $orphan->name, $orphan->term_id
                ));
                $error->setLevel(Error::LEVEL_CRITICAL);
                $error->setSolution('Ordnen Sie den Kategorien eine neue Oberkategorie zu oder machen setzen Sie das Level der KAtegorien hoch.');
                $result->setError($error);
            }
        }

        $this->results->add($result);
    }
}