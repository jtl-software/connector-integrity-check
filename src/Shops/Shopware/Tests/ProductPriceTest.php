<?php
namespace Jtl\Connector\Integrity\Shops\Shopware\Tests;

use Jtl\Connector\Integrity\Models\Test\AbstractTest;
use Jtl\Connector\Integrity\Models\Test\Data;
use Jtl\Connector\Integrity\Models\Test\Result;
use Jtl\Connector\Integrity\Models\Test\TestType;

class ProductPriceTest extends AbstractTest
{
    public function run()
    {
        $this->getResults()->add(
            (new Result())->setType(
                    (new TestType(TestType::DATABASE))
                )
                ->addData(
                (new Data())->setMessage('yolo')
            )
        );
    }
}
