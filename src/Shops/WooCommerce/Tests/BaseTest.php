<?php

namespace Jtl\Connector\Integrity\Shops\WooCommerce\Tests;

use Jtl\Connector\Integrity\Models\Test\AbstractTest;
use wpdb;

abstract class BaseTest extends AbstractTest
{
    const ERROR_CODE_REQUIREMENT = 10;
    const ERROR_CODE_DATA_INCONSISTENCY = 20;

    protected $wpdb;

    public function __construct($sort = 0)
    {
        parent::__construct($sort);

        define('SHORTINIT', true);

        require_once(ROOT_DIR . '/../wp-config.php');

        $this->wpdb = new wpdb(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
    }
}