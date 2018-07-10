<?php
namespace Jtl\Connector\Integrity\Shops\Server;

use Jtl\Connector\Integrity\Models\Test\AbstractTestLoader;
use Jtl\Connector\Integrity\Shops\Server\Tests\OSTest;
use Jtl\Connector\Integrity\Shops\Server\Tests\PHPTest;

class ServerTestLoader extends AbstractTestLoader
{
    /**
     * ShopwareTestLoader constructor.
     */
    public function __construct()
    {
        parent::__construct();
        // removed unnecessary test addTest(new OSTest(-10))
        $this->addTest(new PHPTest(-10));
    }
}
