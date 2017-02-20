<?php

namespace Jtl\Connector\Integrity\Shops\WooCommerce;

use Jtl\Connector\Integrity\Models\Test\AbstractTestLoader;
use Jtl\Connector\Integrity\Shops\WooCommerce\Tests\DuplicatedSkuTest;
use Jtl\Connector\Integrity\Shops\WooCommerce\Tests\OrphanCategoriesTest;
use Jtl\Connector\Integrity\Shops\WooCommerce\Tests\OrphanProductVariationsTest;

class WooCommerceTestLoader extends AbstractTestLoader
{
    function __construct()
    {
        parent::__construct();

        $sort = 1;

        $this->addTest(new DuplicatedSkuTest($sort++));
        $this->addTest(new OrphanProductVariationsTest($sort++));
        $this->addTest(new OrphanCategoriesTest($sort++));
    }
}