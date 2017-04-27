<?php
namespace Jtl\Connector\Integrity\Shops\Gambio\Tests;

use Jtl\Connector\Integrity\Models\Test\Result;
use Jtl\Connector\Integrity\Models\Test\Error;

class I18nTest extends AbstractGambioTest
{
    public function run()
    {
        $this->checkLanguages();
        $this->checkCategoryDescriptions();
        $this->checkProductDescriptions();
    }
    
    private function checkLanguages()
    {
        $result = (new Result())->setName('Gambio Sprachen ISO-Codes');

        $languages = $this->Db()->query('SELECT COUNT(*) AS missing FROM languages WHERE code IS NULL || code = ""');

        if ($languages->fetchColumn() > 0) {
            $result->setError(
                (new Error())->setMessage('In Gambio sind Sprachen angelegt die über keinen gültigen ISO-Code verfügen.')
                    ->setLevel(Error::LEVEL_CRITICAL)
                    ->setSolution('Bitte tragen Sie für alle Sprachen im Shop einen gültigen ISO-Code ein.')
            );
        }
        
        $this->getResults()->add($result);
    }

    private function checkCategoryDescriptions()
    {
        $result = (new Result())->setName('Gambio Kategorie-Übersetzungen Daten-Konsistenz');

        $categories = $this->Db()->query('SELECT c.categories_id, l.languages_id
          FROM categories_description d
          LEFT JOIN categories c ON c.categories_id = d.categories_id
          LEFT JOIN languages l ON l.languages_id = d.language_id
          WHERE (c.categories_id IS NULL || c.categories_id = "") || (l.languages_id IS NULL || l.languages_id = "")');

        if ($categories->rowCount() > 0) {
            $result->setError(
                (new Error())->setMessage('Die Gambio-Datenbank enthält inkonsistente Kategorie-Übersetzungen.')
                    ->setLevel(Error::LEVEL_CRITICAL)
                    ->setSolution('Bitte überprüfen Sie die Tabelle categories_descriptions auf Einträge mit defekten Relationen zu Sprachen und/oder Kategorien.')
            );
        }

        $this->getResults()->add($result);
    }

    private function checkProductDescriptions()
    {
        $result = (new Result())->setName('Gambio Produkt-Übersetzungen Daten-Konsistenz');

        $descriptions = $this->Db()->query('SELECT p.products_id, l.languages_id
          FROM products_description d
          LEFT JOIN products p ON p.products_id = d.products_id
          LEFT JOIN languages l ON l.languages_id = d.language_id
          WHERE (p.products_id IS NULL || p.products_id = "") || (l.languages_id IS NULL || l.languages_id = "")');

        if ($descriptions->rowCount() > 0) {
            $result->setError(
                (new Error())->setMessage('Die Gambio-Datenbank enthält inkonsistente Produkt-Übersetzungen.')
                    ->setLevel(Error::LEVEL_CRITICAL)
                    ->setSolution('Bitte überprüfen Sie die Tabelle products_descriptions auf Einträge mit defekten Relationen zu Sprachen und/oder Produkten.')
            );
        }

        $this->getResults()->add($result);
    }
}
