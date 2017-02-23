<?php
namespace Jtl\Connector\Integrity\Shops\Shopware\Tests;

use Jtl\Connector\Integrity\Exceptions\FileNotExistsException;
use Jtl\Connector\Integrity\Models\Test\AbstractTest;

abstract class AbstractShopwareTest extends AbstractTest
{
    /**
     * @var \PDO
     */
    private static $db;
    
    /**
     * @return \PDO
     * @throws \Exception
     */
    protected function Db()
    {
        if (is_null(self::$db)) {
            $config_path = ROOT_DIR . '/../config.php';
            
            if (!file_exists($config_path)) {
                throw (new FileNotExistsException(sprintf(
                    'Shopware Konfigurationsdatei <code>%s</code> wurde nicht gefunden',
                    $config_path
                )))->setMissingFile($config_path);
            }
            
            $c = require_once($config_path);
            
            self::$db = new \PDO(
                sprintf(
                    'mysql:host=%s;dbname=%s;port=%s',
                    $c['db']['host'],
                    $c['db']['dbname'],
                    $c['db']['port']
                ),
                $c['db']['username'],
                $c['db']['password']
            );
        }
        
        return self::$db;
    }
}
