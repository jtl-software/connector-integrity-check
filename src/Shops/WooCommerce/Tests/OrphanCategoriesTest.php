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
        $result->setType(new TestType(TestType::DATABASE));

        if (empty($orphans)) {
            $data = new Data();
            $data->setMessage('Keine Unterkategorie mit nicht existenter Oberkategorie gefunden');
            $result->addData($data);
        } else {
            foreach ($orphans as $orphan) {
                $error = new Error();
                $error->setCode(self::ERROR_CODE_DATA_INCONSISTENCY);
                $error->setMessage(sprintf(
                    'Die Oberkategorie (%d) von "%s" (%d) existiert nicht',
                    $orphan->parent, $orphan->name, $orphan->term_id
                ));
                $result->addError($error);
            }
        }

        $this->results->add($result);
    }
}