<?php

namespace Jtl\Connector\Integrity\Shops\XtcBase\Tests;

use Jtl\Connector\Integrity\Models\Test\AbstractTest;
use Jtl\Connector\Integrity\Models\Test\Error;
use Jtl\Connector\Integrity\Models\Test\Result;

/**
 * Class PHPTest
 * @package Jtl\Connector\Integrity\Shops\XtcBase\Tests
 */
class PHPTest extends AbstractTest
{
    /**
     *
     */
    public function run()
    {
        $this->checkPharExtension();
    }

    /**
     * Phar Extension
     */
    private function checkPharExtension()
    {
        $result = (new Result())->setName('PHP Phar-Extension')
            ->setDescription('JTL-Connector benötigt PHP-Unterstützung für Phar, die korrekt konfiguriert sein muss.Phar muss z. B. in der Suhosin-Executor-Whitelist erlaubt sein.');

        $check = true;
        if (!class_exists('Phar')) {
            $check = false;
        }

        if (extension_loaded('suhosin')) {
            $whitelist = explode(',', ini_get('suhosin.executor.include.whitelist'));
            if (!in_array('phar', $whitelist)) {
                $check = false;
            }
        }

        if (!$check) {
            $result->setError(
                (new Error())->setMessage('PHP Phar-Extension nicht vorhanden')
                    ->setSolution('Bitte kontaktieren Sie Ihren Hoster oder Administrator')
            );
        }

        $this->getResults()->add($result);
    }
}