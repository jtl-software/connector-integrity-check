<?php
namespace Jtl\Connector\Integrity\Shops\Server;

use Jtl\Connector\Integrity\Models\Test\AbstractTestLoader;
use Jtl\Connector\Integrity\Shops\Server\Tests\OSTest;

class ServerTestLoader extends AbstractTestLoader
{
    /**
     * ShopwareTestLoader constructor.
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->addTest(new OSTest(1));
    }
}
