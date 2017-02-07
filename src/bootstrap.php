<?php
ini_set('display_errors', true);
error_reporting(E_ALL);

spl_autoload_register(function ($class)  {
    $prefix = 'Jtl\\Connector\\Integrity\\';
    $base_dir = __DIR__ . '/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

use Jtl\Connector\Integrity\IntegrityCheck;

/**
 * @return IntegrityCheck
 */
function integrity()
{
    return IntegrityCheck::init();
}

integrity()->run();
