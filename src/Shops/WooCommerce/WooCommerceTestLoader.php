<?php

namespace Jtl\Connector\Integrity\Shops\WooCommerce;

use Jtl\Connector\Integrity\Models\Test\AbstractTestLoader;
use Jtl\Connector\Integrity\Shops\WooCommerce\Tests\DuplicatedSkuTest;
use Jtl\Connector\Integrity\Shops\WooCommerce\Tests\OrphanCategoriesTest;
use Jtl\Connector\Integrity\Shops\WooCommerce\Tests\OrphanVarCombisTest;
use Jtl\Connector\Integrity\Shops\WooCommerce\Tests\ProductsWithoutCategoriesTest;
use Jtl\Connector\Integrity\Shops\WooCommerce\Tests\VarCombiChildrenWithSimpleFatherTest;
use Jtl\Connector\Integrity\Shops\WooCommerce\Tests\VarCombiProductsWithoutVariationsTest;

class WooCommerceTestLoader extends AbstractTestLoader
{
    public function __construct()
    {
        parent::__construct();

        $sort = 1;

        $this->addTest(new OrphanCategoriesTest($sort++));
        $this->addTest(new ProductsWithoutCategoriesTest($sort++));
        $this->addTest(new DuplicatedSkuTest($sort++));
        $this->addTest(new OrphanVarCombisTest($sort++));
        $this->addTest(new VarCombiChildrenWithSimpleFatherTest($sort++));
        $this->addTest(new VarCombiProductsWithoutVariationsTest($sort++));
    }
}
