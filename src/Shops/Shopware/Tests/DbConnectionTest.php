<?php
namespace Jtl\Connector\Integrity\Shops\Shopware\Tests;

use Jtl\Connector\Integrity\Exceptions\FileNotExistsException;
use Jtl\Connector\Integrity\Models\Test\Result;
use Jtl\Connector\Integrity\Models\Test\Error;

class DbConnectionTest extends AbstractShopwareTest
{
    public function run()
    {
        $this->checkConnection();
    }
    
    private function checkConnection()
    {
        $result = (new Result())->setName('Shopware Datenbank-Verbindung');
        
        try {
            $db = $this->Db();
            if (!($db instanceof \PDO)) {
                $result->setError(
                    (new Error())->setMessage('Es konnte keine Verbindung zur Shopware Datenbank hergestellt werden')
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
}
