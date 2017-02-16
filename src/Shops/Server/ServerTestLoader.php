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
        
        $this->addTest(new OSTest(-10))
            ->addTest(new PHPTest(-9));
    }
}
