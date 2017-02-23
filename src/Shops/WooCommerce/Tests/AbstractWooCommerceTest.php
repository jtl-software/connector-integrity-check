<?php

namespace Jtl\Connector\Integrity\Shops\WooCommerce\Tests;

use Jtl\Connector\Integrity\Models\Test\AbstractTest;
use Jtl\Connector\Integrity\Models\Test\Error;
use Jtl\Connector\Integrity\Models\Test\Result;

abstract class AbstractWooCommerceTest extends AbstractTest
{
    public function __construct($sort = 0)
    {
        parent::__construct($sort);

        $config_path = ROOT_DIR . '/../wp-config.php';

        if (!file_exists($config_path)) {
            throw new \Exception(sprintf(
                'WooCommerce config <code>%s</code> not found',
                $config_path
            ));
        }

        require_once($config_path);
    }

    protected function createResult($name, $description)
    {
        $result = new Result($this->sort);
        $result->setName($name);
        $result->setDescription($description);

        return $result;
    }

    protected function addErrorToResult(Result &$result, $message, $solution, $level)
    {
        $error = new Error();
        $error->setMessage($message);
        $error->setLevel($level);
        $error->setSolution($solution);
        $result->setError($error);
    }
}
