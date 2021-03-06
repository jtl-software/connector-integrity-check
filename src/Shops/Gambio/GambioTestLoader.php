<?php
namespace Jtl\Connector\Integrity\Shops\Gambio;

use Jtl\Connector\Integrity\Shops\Gambio\Tests\I18nTest;
use Jtl\Connector\Integrity\Shops\Gambio\Tests\InstallationTest;
use Jtl\Connector\Integrity\Shops\XtcBase\AbstractXtcBaseTestLoader;

class GambioTestLoader extends AbstractXtcBaseTestLoader
{
    /**
     * GambioTestLoader constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->addTest(new InstallationTest(1));
        $this->addTest(new I18nTest(2));
    }
}
