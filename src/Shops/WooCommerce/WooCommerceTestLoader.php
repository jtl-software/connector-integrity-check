<?php

namespace Jtl\Connector\Integrity\Shops\WooCommerce;

use Jtl\Connector\Integrity\Models\Test\AbstractTestLoader;
use Jtl\Connector\Integrity\Shops\WooCommerce\Tests\WpPostsTest;

class WooCommerceTestLoader extends AbstractTestLoader
{
    function __construct()
    {
        $this->addTest(new WpPostsTest(1));
    }
}