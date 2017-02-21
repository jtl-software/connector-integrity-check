<?php
namespace Jtl\Connector\Integrity\Shops\Shopware\Tests;

use Jtl\Connector\Integrity\Models\Test\AbstractTest;
use Jtl\Connector\Integrity\Models\Test\Result;

class ProductPriceTest extends AbstractTest
{
    public function run()
    {
        sleep(2);
        
        $this->checkMissingPrices();
    }
    
    protected function checkMissingPrices()
    {
        $result = (new Result())->setName('Shopware missing product prices');
    
        $this->getResults()->add($result);
    }
}
