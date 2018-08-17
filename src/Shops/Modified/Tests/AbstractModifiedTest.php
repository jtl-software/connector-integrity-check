<?php
namespace Jtl\Connector\Integrity\Shops\Modified\Tests;

use Jtl\Connector\Integrity\Exceptions\FileNotExistsException;
use Jtl\Connector\Integrity\Models\Test\AbstractTest;

abstract class AbstractModifiedTest extends AbstractTest
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
            $config_path = ROOT_DIR . '/../includes/configure.php';

            if (!file_exists($config_path)) {
                throw (new FileNotExistsException(sprintf(
                    'Modified Konfigurationsdatei <code>%s</code> wurde nicht gefunden',
                    $config_path
                )))->setMissingFile($config_path);
            }
            
            $c = require_once($config_path);
            
            self::$db = new \PDO(
                sprintf(
                    'mysql:host=%s;dbname=%s',
                    DB_SERVER,
                    DB_DATABASE
                ),
                DB_SERVER_USERNAME,
                DB_SERVER_PASSWORD
            );
        }
        
        return self::$db;
    }
}
