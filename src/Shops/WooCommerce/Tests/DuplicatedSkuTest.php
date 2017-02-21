<?php

namespace Jtl\Connector\Integrity\Shops\WooCommerce\Tests;

use Jtl\Connector\Integrity\Models\Test\Data;
use Jtl\Connector\Integrity\Models\Test\Error;
use Jtl\Connector\Integrity\Models\Test\Result;
use Jtl\Connector\Integrity\Models\Test\TestType;

class DuplicatedSkuTest extends BaseTest
{
    public function run()
    {
        global $wpdb;

        $duplicates = $wpdb->get_results(sprintf("
            SELECT post_id, meta_value
            FROM %s
            WHERE meta_key = '_sku' AND meta_value IN
            (
                SELECT meta_value FROM %s 
                WHERE meta_key = '_sku' and meta_value IS NOT NULL AND meta_value != '' 
                GROUP BY meta_value 
                HAVING COUNT(*) > 1
            )
            ORDER BY meta_value",
            $wpdb->postmeta, $wpdb->postmeta
        ));

        $result = new Result($this->sort);
        $result->setName('Doppelte SKUs');
        $result->setDescription('JTL-Wawi erlaubt keine doppelten SKUs.');

        if (!empty($duplicates)) {
            $messages = [];
            $skuDuplicates = [];

            foreach ($duplicates as $duplicate) {
                $skuDuplicates[$duplicate->meta_value][] = $duplicate->post_id;
            }

            foreach ($skuDuplicates as $sku => $productIds) {
                $messages[] = sprintf(
                    'Die SKU "%s" wird von mehreren Produkten verwendet: %s',
                    $sku, implode(", ", $productIds)
                );
            }

            $error = new Error();
            $error->setMessage(implode('<br>', $messages));
            $error->setLevel(Error::LEVEL_CRITICAL);
            $error->setSolution('Vergeben Sie eindeutige SKUs für alle Artikel.');
            $result->setError($error);
        }

        $this->results->add($result);
    }
}