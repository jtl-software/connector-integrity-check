<?php
namespace Jtl\Connector\Integrity\Shops\Gambio\Tests;

use Jtl\Connector\Integrity\Exceptions\FileNotExistsException;
use Jtl\Connector\Integrity\Models\Test\Data;
use Jtl\Connector\Integrity\Models\Test\Result;
use Jtl\Connector\Integrity\Models\Test\Error;

class InstallationTest extends AbstractGambioTest
{
    private static $minVersion = '3.9';

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
            $releasePath = ROOT_DIR . '/../release_info.php';

            if (!file_exists($releasePath)) {
                throw (new FileNotExistsException(sprintf(
                    'Gambio Release-Info Datei <code>%s</code> wurde nicht gefunden',
                    $releasePath
                )))->setMissingFile($releasePath);
            }

            $r = require_once($releasePath);
            $v = ltrim($gx_version,'v');

            $result->setData(
                (new Data())->setExpected('>= ' . static::$minVersion)
                    ->setActual($v)
            );

            if (version_compare($v, static::$minVersion, '<')) {
                $result->setError(
                    (new Error())->setMessage('Der Connector ist nicht mit Ihrer Gambio-Version kompatibel.')
                        ->setLevel(Error::LEVEL_CRITICAL)
                        ->setSolution('Bitte updaten Sie Ihre Gambio-Installation auf mindestens GX ' . static::$minVersion . ' oder neuer.')
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
