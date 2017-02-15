<?php
namespace Jtl\Connector\Integrity\Shops\Shopware;

use Jtl\Connector\Integrity\Models\Test\AbstractTestLoader;
use Jtl\Connector\Integrity\Shops\Shopware\Tests\ProductPriceTest;

class ShopwareTestLoader extends AbstractTestLoader
{
    /**
     * ShopwareTestLoader constructor.
     */
    public function __construct()
    {
        $this->addTest(new ProductPriceTest(1));
    }
}
