<?php
namespace Jtl\Connector\Integrity\Shops\Shopware\Tests;

use Jtl\Connector\Integrity\Models\Test\Error;
use Jtl\Connector\Integrity\Models\Test\Result;

class CustomerOrderMissingRelationsTest extends AbstractShopwareTest
{
    public function run()
    {
        
    }
    
    /**
     * Check s_articles --> s_articles_details relation
     */
    private function checkPayments()
    {
        $result = (new Result())->setName('Produkte ohne Einträge in der s_articles_details Tabelle');
        
        $stmt = $this->Db()->prepare('SELECT a.id
                                        FROM s_articles a
                                        LEFT JOIN s_articles_details d ON d.articleID = a.id
                                        WHERE d.id IS NULL');
        
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $result->setError(
                (new Error())->setMessage(sprintf(
                    'Es wurden <code>%s</code> Artikel gefunden, die keine Einträge in <code>%s</code> haben',
                    $stmt->rowCount(),
                    's_articles_details'
                ))
                    ->setSolution('Einige Artikel können nicht gezogen werden, da Daten fehlen. Bitte bereinigen Sie Ihre Datenbank.')
            );
        }
        
        $this->getResults()->add($result);
    }
}
