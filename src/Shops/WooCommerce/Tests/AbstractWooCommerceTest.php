<?php

namespace Jtl\Connector\Integrity\Shops\WooCommerce\Tests;

use Jtl\Connector\Integrity\Exceptions\FileNotExistsException;
use Jtl\Connector\Integrity\Models\Test\AbstractTest;
use Jtl\Connector\Integrity\Models\Test\Error;
use Jtl\Connector\Integrity\Models\Test\Result;

abstract class AbstractWooCommerceTest extends AbstractTest
{
    /**
     * @param int $sort
     * @throws FileNotExistsException
     */
    public function __construct($sort = 0)
    {
        parent::__construct($sort);
    }

    protected function createResult($name, $description)
    {
        $result = new Result($this->sort);
        $result->setName($name);
        $result->setDescription($description);

        return $result;
    }

    protected function addErrorToResult(Result &$result, $message, $solution = '', $level = Error::LEVEL_CRITICAL)
    {
        $error = new Error();
        $error->setMessage($message);
        $error->setLevel($level);
        $error->setSolution($solution);
        $result->setError($error);
    }

    /**
     * @throws FileNotExistsException
     */
    protected function requireConfigFile()
    {
        //standalone
        $configPath = dirname(ROOT_DIR) . '/wp-config.php';

        if (!file_exists($configPath) && defined('ABSPATH')) {
            $configPath = rtrim(ABSPATH, '/') . '/wp-config.php';
        }

        if (!file_exists($configPath)) {
            throw (new FileNotExistsException(sprintf(
                'WordPress Konfigurationsdatei <code>%s</code> wurde nicht gefunden',
                $configPath
            )))->setMissingFile($configPath);
        }

        require_once($configPath);
    }
}
