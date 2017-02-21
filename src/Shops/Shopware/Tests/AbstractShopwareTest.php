<?php
namespace Jtl\Connector\Integrity\Shops\Shopware\Tests;

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
            //$config_path = ROOT_DIR . '/../config.php';
            // @TODO: Insert valid path
            $config_path = '/var/www/sw52/config.php';
            
            if (!file_exists($config_path)) {
                throw new \Exception(sprintf(
                    'Shopware config "%s" not found',
                    $config_path
                ));
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
