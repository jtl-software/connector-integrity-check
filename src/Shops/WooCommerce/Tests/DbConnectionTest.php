<?php

namespace Jtl\Connector\Integrity\Shops\WooCommerce\Tests;

use Jtl\Connector\Integrity\Exceptions\FileNotExistsException;
use Jtl\Connector\Integrity\Models\Test\Error;

class DbConnectionTest extends AbstractWooCommerceTest
{
    public function run()
    {
        try {
            $result = $this->createResult(
                'WordPress Datenbank-Verbindung',
                'Eine Datenbankverbindung ist notwendig um die Integritätstests druchführen zu können.'
            );

            $this->requireConfigFile();
            global $wpdb;

            if (is_null($wpdb)) {
                $this->addErrorToResult(
                    $result,
                    'Es konnte keine Verbindung zur Datenbank hergestellt werden',
                    'Bitte kontaktieren Sie Ihren Hoster oder Administrator',
                    Error::LEVEL_CRITICAL
                );
            }
        } catch (FileNotExistsException $e) {
            $this->addErrorToResult(
                $result,
                $e->getMessage(),
                'Bitte legen Sie den Systemtest Ordner in Ihr Shop Root Verzeichnis',
                Error::LEVEL_CRITICAL
            );
        } catch (\Exception $e) {
            $this->addErrorToResult(
                $result,
                $e->getMessage(),
                'Bitte kontaktieren Sie Ihren Hoster oder Administrator',
                Error::LEVEL_CRITICAL
            );
        }

        $this->results->add($result);
    }
}
