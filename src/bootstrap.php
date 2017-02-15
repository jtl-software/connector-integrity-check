<?php
ini_set('display_errors', true);
error_reporting(E_ALL);

define('ROOT_DIR', realpath(__DIR__ . '/../'));

include ROOT_DIR . '/vendor/autoload.php';

use Jtl\Connector\Integrity\IntegrityCheck;

/**
 * @return IntegrityCheck
 */
function integrity()
{
    return IntegrityCheck::init();
}

// @TODO: Register your tests
class Test extends \Jtl\Connector\Integrity\Models\Test\AbstractTest
{
    public function run()
    {
        // Bla magic stuff
        $this->results->add(
            (new \Jtl\Connector\Integrity\Models\Test\Result())
                ->setType(
                    (new \Jtl\Connector\Integrity\Models\Test\TestType(\Jtl\Connector\Integrity\Models\Test\TestType::REQUIREMENTS))
                )
                ->addData(
                    (new \Jtl\Connector\Integrity\Models\Test\Data())
                        ->setMessage('Junge, hat geklappt')
                )
        );
    }
}

integrity()->registerTest(
    (new Test())
);

integrity()->run();
