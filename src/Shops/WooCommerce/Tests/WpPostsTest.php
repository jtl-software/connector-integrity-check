<?php

namespace Jtl\Connector\Integrity\Shops\WooCommerce\Tests;

use Jtl\Connector\Integrity\Models\Test\Data;
use Jtl\Connector\Integrity\Models\Test\Error;
use Jtl\Connector\Integrity\Models\Test\Result;
use Jtl\Connector\Integrity\Models\Test\TestType;

class WpPostsTest extends BaseTest
{
    public function run()
    {
        $orphans = $this->wpdb->get_results("
            SELECT ID, post_title, post_parent
            FROM wp_posts
            WHERE post_type = 'product_variation' AND post_parent NOT IN
            (
              SELECT ID FROM wp_posts WHERE post_type = 'product'
            )
        ");

        $result = new Result($this->sort);
        $result->setType(new TestType(TestType::DATABASE));

        if (empty($orphans)) {
            $data = new Data();
            $data->setMessage('Kein Kindartikel ohne Vaterartikel gefunden');
            $result->addData($data);
        } else {
            foreach ($orphans as $orphan) {
                $error = new Error();
                $error->setCode(self::ERROR_CODE_DATA_INCONSISTENCY);
                $error->setMessage(sprintf(
                    'Der Vateratikel (#%d) von "%s" (#%d) existiert nicht',
                    $orphan->post_parent, $orphan->post_title, $orphan->ID
                ));
                $result->addError($error);
            }
        }

        return $result;
    }
}