<?php
namespace Jtl\Connector\Integrity\Shops\Gambio;

use Jtl\Connector\Integrity\Models\Test\AbstractTestLoader;
use Jtl\Connector\Integrity\Shops\Gambio\Tests\ProductPriceTest;

class GambioTestLoader extends AbstractTestLoader
{
    /**
     * GambioTestLoader constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->addTest(new ProductPriceTest(1));
    }
}
