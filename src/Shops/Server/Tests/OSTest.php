<?php
namespace Jtl\Connector\Integrity\Shops\Server\Tests;

use Jtl\Connector\Integrity\Models\Test\AbstractTest;
use Jtl\Connector\Integrity\Models\Test\Data;
use Jtl\Connector\Integrity\Models\Test\Result;
use Jtl\Connector\Integrity\Models\Test\TestType;

class OSTest extends AbstractTest
{
    public function run()
    {
        sleep(2);
        
        $this->getResults()->add(
            (new Result())->setType(
                (new TestType(TestType::REQUIREMENTS))
            )
                ->addData(
                    (new Data())->setMessage('OS ok')
                )
        );
    }
}
