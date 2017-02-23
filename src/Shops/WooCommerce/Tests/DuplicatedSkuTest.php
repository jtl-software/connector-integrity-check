<?php

namespace Jtl\Connector\Integrity\Shops\WooCommerce\Tests;

use Jtl\Connector\Integrity\Models\Test\Error;

class DuplicatedSkuTest extends AbstractWooCommerceTest
{
    public function run()
    {
        global $wpdb;

        $duplicates = $wpdb->get_results("
            SELECT meta_value as sku, COUNT(*) as total
            FROM `$wpdb->postmeta`
            WHERE meta_key = '_sku' AND meta_value IS NOT NULL AND meta_value != '' 
            GROUP BY meta_value
            HAVING COUNT(meta_value) > 1
            ORDER BY meta_value"
        );

        $result = $this->createResult(
            'Doppelte SKUs',
            'JTL-Wawi erlaubt keine doppelten SKUs.'
        );

        if (!empty($duplicates)) {
            $messages = [];

            foreach ($duplicates as $duplicate) {
                $messages[] = "Die SKU '{$duplicate->sku}' wird von insgesamt {$duplicate->total} Artikeln verwendet";
            }

            $this->addErrorToResult(
                $result,
                implode('<br>', $messages),
                'Vergeben Sie eindeutige SKUs für alle Artikel.',
                Error::LEVEL_CRITICAL
            );
        }

        $this->results->add($result);
    }
}
