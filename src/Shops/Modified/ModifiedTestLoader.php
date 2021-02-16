<?php
namespace Jtl\Connector\Integrity\Shops\Modified;

use Jtl\Connector\Integrity\Shops\Modified\Tests\I18nTest;
use Jtl\Connector\Integrity\Shops\Modified\Tests\InstallationTest;
use Jtl\Connector\Integrity\Shops\XtcBase\AbstractXtcBaseTestLoader;

class ModifiedTestLoader extends AbstractXtcBaseTestLoader
{
    /**
     * ModifiedTestLoader constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->addTest(new InstallationTest(1));
        $this->addTest(new I18nTest(2));
    }
}
