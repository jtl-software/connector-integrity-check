<?php
namespace Jtl\Connector\Integrity\Shops\Gambio\Tests;

use Jtl\Connector\Integrity\Exceptions\FileNotExistsException;
use Jtl\Connector\Integrity\Models\Test\Data;
use Jtl\Connector\Integrity\Models\Test\Result;
use Jtl\Connector\Integrity\Models\Test\Error;

class InstallationTest extends AbstractGambioTest
{
    private static $min_version = '2.5';

    public function run()
    {
        $this->checkConnection();
        $this->checkVersion();
    }
    
    private function checkConnection()
    {
        $result = (new Result())->setName('Gambio Konfiguration und Datenbank-Verbindung');
        
        try {
            $db = $this->Db();
            if (!($db instanceof \PDO)) {
                $result->setError(
                    (new Error())->setMessage('Es konnte keine Verbindung zur Gambio Datenbank hergestellt werden')
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
        $result = (new Result())->setName('Gambio Version');

        try {
            $release_path = ROOT_DIR . '/../release_info.php';

            if (!file_exists($release_path)) {
                throw (new FileNotExistsException(sprintf(
                    'Gambio Release-Info Datei <code>%s</code> wurde nicht gefunden',
                    $release_path
                )))->setMissingFile($release_path);
            }

            $r = require_once($release_path);
            $v = ltrim($gx_version,'v');

            $result->setData(
                (new Data())->setExpected('>= ' . static::$min_version)
                    ->setActual($v)
            );

            if (version_compare($v, static::$min_version, '<')) {
                $result->setError(
                    (new Error())->setMessage('Der Connector ist nicht mit Ihrer Gambio-Version kompatibel.')
                        ->setLevel(Error::LEVEL_CRITICAL)
                        ->setSolution('Bitte updaten Sie Ihre Gambio-Installation auf mindestens GX ' . static::$min_version . ' oder neuer.')
                );
            }
        } catch (FileNotExistsException $e) {
            $result->setError(
                (new Error())->setMessage($e->getMessage())
            );
        }

        $this->getResults()->add($result);
    }
}
