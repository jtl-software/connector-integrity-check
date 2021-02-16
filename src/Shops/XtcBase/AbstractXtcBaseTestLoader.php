<?php
namespace Jtl\Connector\Integrity\Shops\XtcBase;

use Jtl\Connector\Integrity\Models\Test\AbstractTestLoader;
use Jtl\Connector\Integrity\Shops\XtcBase\Tests\PHPTest;

abstract class AbstractXtcBaseTestLoader extends AbstractTestLoader
{
    /**
     * ShopwareTestLoader constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->addTest(new PHPTest());
    }
}
