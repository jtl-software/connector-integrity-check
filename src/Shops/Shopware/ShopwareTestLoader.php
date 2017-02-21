<?php
namespace Jtl\Connector\Integrity\Shops\Shopware;

use Jtl\Connector\Integrity\Models\Test\AbstractTestLoader;
use Jtl\Connector\Integrity\Shops\Shopware\Tests\DbConnectionTest;
use Jtl\Connector\Integrity\Shops\Shopware\Tests\ProductMissingRelationsTest;
use Jtl\Connector\Integrity\Shops\Shopware\Tests\ProductPriceTest;

class ShopwareTestLoader extends AbstractTestLoader
{
    /**
     * ShopwareTestLoader constructor.
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->addTest(new DbConnectionTest(1));
        $this->addTest(new ProductMissingRelationsTest(2));
        $this->addTest(new ProductPriceTest(3));
    }
}
