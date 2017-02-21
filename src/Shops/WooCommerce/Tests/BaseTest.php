<?php

namespace Jtl\Connector\Integrity\Shops\WooCommerce\Tests;

use Jtl\Connector\Integrity\Models\Test\AbstractTest;

abstract class BaseTest extends AbstractTest
{
    public function __construct($sort = 0)
    {
        parent::__construct($sort);

        require_once(ROOT_DIR . '/../wp-config.php');
    }
}