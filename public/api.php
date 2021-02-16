<?php
require_once (dirname(__DIR__) . '/bootstrap.php');

/**
 * @return IntegrityCheck
 */
function integrity()
{
    return \Jtl\Connector\Integrity\IntegrityCheck::init();
}

integrity()->run();