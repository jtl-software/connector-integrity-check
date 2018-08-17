<?php
namespace Jtl\Connector\Integrity\Shops\Modified\Tests;

use Jtl\Connector\Integrity\Exceptions\FileNotExistsException;
use Jtl\Connector\Integrity\Models\Test\Data;
use Jtl\Connector\Integrity\Models\Test\Result;
use Jtl\Connector\Integrity\Models\Test\Error;

class InstallationTest extends AbstractModifiedTest
{
    private static $min_version = '2.0';

    public function run()
    {
        $this->checkConnection();
        $this->checkVersion();
    }
    
    private function checkConnection()
    {
        $result = (new Result())->setName('Modified Konfiguration und Datenbank-Verbindung');
        
        try {
            $db = $this->Db();
            if (!($db instanceof \PDO)) {
                $result->setError(
                    (new Error())->setMessage('Es konnte keine Verbindung zur Modified Datenbank hergestellt werden')
                        ->setLevel(Error::LEVEL_CRITICAL)
                        ->setSolution('Bitte kontaktieren Sie Ihren Hoster oder Administrator')
                );
            }
        } catch (FileNotExistsException $e) {
            $result->setError(
                (new Error())->setMessage($e->getMessage())
                    ->setLevel(Error::LEVEL_CRITICAL)
                    ->setSolution('Bitte legen Sie den Systemtest Ordner in Ihr Shop Root Verzeichnis')
            );
        } catch (\Exception $e) {
            $result->setError(
                (new Error())->setMessage($e->getMessage())
                    ->setLevel(Error::LEVEL_CRITICAL)
                    ->setSolution('Bitte kontaktieren Sie Ihren Hoster oder Administrator')
            );
        }
        
        $this->getResults()->add($result);
    }

    private function checkVersion()
    {
        $result = (new Result())->setName('Modified Version');

        define('_VALID_XTC', true);

        foreach (new \DirectoryIterator(ROOT_DIR . '/..') as $shoproot) {
            if (!$shoproot->isDot() && $shoproot->isDir() && is_file(ROOT_DIR.'/../'.$shoproot->getFilename().'/check_update.php')) {
                include(ROOT_DIR.'/../'.$shoproot->getFilename().'/includes/version.php');
                break;
            }
        }

        if (!defined('PROJECT_MAJOR_VERSION')) {
            $result->setError(
                (new Error())->setMessage('Die Vesion ihres Shops konnte nicht erkannt werden.')
                    ->setLevel(Error::LEVEL_CRITICAL)
                    ->setSolution('Bitte stellen Sie sicher dass in Ihrem Admin-Verzeichnis die Datei <code>version.php</code> vorhanden und lesbar ist.')
            );
        } else {
            $result->setData(
                (new Data())->setExpected('>= ' . static::$min_version)
                    ->setActual(PROJECT_MAJOR_VERSION.'.'.PROJECT_MINOR_VERSION)
            );

            if (version_compare(PROJECT_MAJOR_VERSION.'.'.PROJECT_MINOR_VERSION, static::$min_version, '<')) {
                $result->setError(
                    (new Error())->setMessage('Der Connector ist nicht mit Ihrer Modified-Version kompatibel.')
                        ->setLevel(Error::LEVEL_CRITICAL)
                        ->setSolution('Bitte updaten Sie Ihre Modified-Installation auf mindestens ' . static::$min_version . ' oder neuer.')
                );
            }
        }

        $this->getResults()->add($result);
    }
}
