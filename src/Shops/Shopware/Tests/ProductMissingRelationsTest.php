<?php
namespace Jtl\Connector\Integrity\Shops\Shopware\Tests;

use Jtl\Connector\Integrity\Models\Test\Result;

class ProductMissingRelationsTest extends AbstractShopwareTest
{
    public function run()
    {
        $this->checkMainToDetail();
        $this->checkDetailToMain();
    }
    
    private function checkMainToDetail()
    {
        $result = (new Result())->setName('Produkte ohne Einträge in der s_articles_details Tabelle');
        
        $this->getResults()->add($result);
    }
    
    private function checkDetailToMain()
    {
        $result = (new Result())->setName('Produkte (Details) ohne Einträge in der s_articles Tabelle');
    
        $this->getResults()->add($result);
    }
}
