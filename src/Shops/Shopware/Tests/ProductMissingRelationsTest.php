<?php
namespace Jtl\Connector\Integrity\Shops\Shopware\Tests;

use Jtl\Connector\Integrity\Models\Test\Error;
use Jtl\Connector\Integrity\Models\Test\Result;

class ProductMissingRelationsTest extends AbstractShopwareTest
{
    public function run()
    {
        $this->checkMainToDetail();
        $this->checkDetailToMain();
        $this->checkFilterGroup();
        $this->checkCategories();
    }
    
    /**
     * Check s_articles --> s_articles_details relation
     */
    private function checkMainToDetail()
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
    
    /**
     * Check s_articles_details --> s_articles relation
     */
    private function checkDetailToMain()
    {
        $result = (new Result())->setName('Produkte (Details) ohne Einträge in der s_articles Tabelle');
    
        $stmt = $this->Db()->prepare('SELECT d.id
                                        FROM s_articles_details d
                                        LEFT JOIN s_articles a ON d.articleID = a.id
                                        WHERE a.id IS NULL');
    
        $stmt->execute();
        if ($stmt->rowCount()) {
            $result->setError(
                (new Error())->setMessage(sprintf(
                    'Es wurden <code>%s</code> Artikel (Details) gefunden, die keine Einträge in <code>%s</code> haben',
                    $stmt->rowCount(),
                    's_articles'
                ))
                    ->setSolution('Einige Artikel können nicht gezogen werden, da Daten fehlen. Bitte bereinigen Sie Ihre Datenbank.')
            );
        }
        
        $this->getResults()->add($result);
    }
    
    /**
     * Check s_articles --> s_filter
     */
    private function checkFilterGroup()
    {
        $result = (new Result())->setName('Produkte ohne Einträge in der s_filter Tabelle');
    
        $stmt = $this->Db()->prepare('SELECT d.ordernumber, a.id
                                        FROM s_articles a
                                        JOIN s_articles_details d ON d.articleID = a.id
                                          AND ((a.configurator_set_id > 0 AND d.kind = 0)
                                          OR (a.configurator_set_id IS NULL AND d.id = a.main_detail_id))
                                        LEFT JOIN s_filter f ON f.id = a.filtergroupID
                                        WHERE f.name IS NULL AND a.filtergroupID IS NOT NULL');
    
        $stmt->execute();
        if ($stmt->rowCount()) {
            $result->setError(
                (new Error())->setMessage(sprintf(
                    'Es wurden <code>%s</code> Artikel gefunden, die keine Einträge in <code>%s</code> haben',
                    $stmt->rowCount(),
                    's_filter'
                ))
                    ->setSolution(sprintf(
                        'Bitte kontrollieren Sie ob die IDs in der Tabelle <code>%s</code>, Spalte <code>%s</code>, auch in der Tabelle <code>%s</code>, Spalte <code>%s</code> existieren.
                         Falls nicht, setzen Sie die Spalte <code>%s</code> entweder auf den korrekten Wert oder auf NULL (kein String)',
                        's_articles',
                        'filtergroupID',
                        's_filter',
                        'id',
                        'filtergroupID'
                    ))
            );
        }
    
        $this->getResults()->add($result);
    }
    
    /**
     * Check s_articles --> s_articles_categories
     */
    private function checkCategories()
    {
        $result = (new Result())->setName('Produkte ohne Einträge in der s_filter Tabelle');
        
        $stmt = $this->Db()->prepare('SELECT a.id
                                        FROM s_articles a
                                        LEFT JOIN s_articles_categories ac ON ac.articleID = a.id
                                        LEFT JOIN s_categories c ON c.id = ac.categoryID
                                        WHERE c.id IS NULL');
        
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $result->setError(
                (new Error())->setMessage(sprintf(
                    'Es wurden <code>%s</code> Artikel gefunden, die keine Einträge in <code>%s</code> oder <code>%s</code> haben',
                    $stmt->rowCount(),
                    's_articles_categories',
                    's_categories'
                ))
                    ->setSolution(sprintf(
                        'Bitte kontrollieren Sie ob die IDs in der Artikel-Kategorie Tabelle <code>%s</code>, auch in der Tabelle <code>%s</code> existieren.
                         Falls nicht, bereinigen Sie bitte Ihre Datenbank',
                        's_articles_categories',
                        's_articles und s_categories'
                    ))
            );
        }
        
        $this->getResults()->add($result);
    }
}
