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

integrity()->run();
