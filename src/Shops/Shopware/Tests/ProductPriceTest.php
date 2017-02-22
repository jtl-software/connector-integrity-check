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
    
    /**
     * Check if every product has at least one price
     */
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
                    'Es wurden <code>%s</code> Artikel gefunden, die keine Einträge in <code>%s</code> haben',
                    $stmt->rowCount(),
                    's_articles_prices'
                ))
                    ->setSolution('Bitte überprüfen Sie alle Artikel, ob diese über min. einen Preis verfügen. Falls nicht, bereinigen Sie Ihre Datenbank.')
            );
        }
    
        $this->getResults()->add($result);
    }
    
    /**
     * Check if every product has got prices for all customer groups
     */
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
                    'Es wurden <code>%s</code> Artikel gefunden, bei denen nicht für alle Kundengruppen Einträge in <code>%s</code> vorhanden sind',
                    $stmt->rowCount(),
                    's_articles_prices'
                ))
                    ->setSolution('')
                    ->setLevel(Error::LEVEL_WARNING)
            );
        }
    
        $this->getResults()->add($result);
    }
}
