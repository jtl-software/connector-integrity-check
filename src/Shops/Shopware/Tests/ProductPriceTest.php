<?php
namespace Jtl\Connector\Integrity\Shops\Shopware\Tests;

use Jtl\Connector\Integrity\Models\Test\Result;

class ProductPriceTest extends AbstractShopwareTest
{
    public function run()
    {
        $this->checkMissingPrices();
    }
    
    private function checkMissingPrices()
    {
        $result = (new Result())->setName('Shopware Produktpreise für Kundengruppen');
    
        $this->getResults()->add($result);
    }
}
