<?php
namespace Jtl\Connector\Integrity\Shops\Shopware\Tests;

use Jtl\Connector\Integrity\Models\Test\Error;
use Jtl\Connector\Integrity\Models\Test\Result;

class ProductPriceTest extends AbstractShopwareTest
{
    public function run()
    {
        $this->checkMissingPrices();
        $this->checkMissingCustomerGroups();
    }
    
    private function checkMissingPrices()
    {
        $result = (new Result())->setName('Produkte ohne Einträge in der s_articles_prices (Preise) Tabelle');
    
        $stmt = $this->Db()->prepare('SELECT a.id
                                        FROM s_articles a
                                        LEFT JOIN s_articles_details d ON d.articleID = a.id
                                        LEFT JOIN s_articles_prices p ON p.articleID = a.id
                                            AND p.articledetailsID = d.id
                                        WHERE p.id IS NULL');
    
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $result->setError(
                (new Error())->setMessage(sprintf(
                    'Es wurden %s Artikel gefunden, die keine Einträge in s_articles_prices haben',
                    $stmt->rowCount()
                ))
                    ->setSolution('')
            );
        }
    
        $this->getResults()->add($result);
    }
    
    private function checkMissingCustomerGroups()
    {
        $result = (new Result())->setName('Fehlende Kundengruppen in der s_articles_prices (Preise) Tabelle');
    
        $stmt = $this->Db()->prepare('SELECT a.id, d.id as detail_id
                                        FROM s_articles a
                                        JOIN s_articles_details d ON d.articleID = a.id
                                        JOIN s_core_shops s
                                        JOIN s_core_customergroups g ON g.id = s.customer_group_id
                                        LEFT JOIN s_articles_prices p ON p.articleID = a.id
                                            AND p.articledetailsID = d.id
                                            AND p.pricegroup = g.groupkey
                                        WHERE p.id IS NULL
                                        GROUP BY p.id');
    
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $result->setError(
                (new Error())->setMessage(sprintf(
                    'Es wurden %s Artikel gefunden, bei denen nicht für alle Kundengruppen Einträge in s_articles_prices vorhanden sind',
                    $stmt->rowCount()
                ))
                    ->setSolution('')
            );
        }
    
        $this->getResults()->add($result);
    }
}
