<?php
namespace Jtl\Connector\Integrity\Shops\Shopware\Tests;

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
                        ->setSolution('Bitte kontaktieren Sie Ihren Hoster oder Administrator')
                );
            }
        } catch (\Exception $e) {
            $result->setError(
                (new Error())->setMessage($e->getMessage())
                    ->setSolution('Bitte kontaktieren Sie Ihren Hoster oder Administrator')
            );
        }
        
        $this->getResults()->add($result);
    }
}
