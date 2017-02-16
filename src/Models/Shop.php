<?php
namespace Jtl\Connector\Integrity\Models;

final class Shop
{
    const SERVER = 'server';
    const SHOPWARE = 'shopware';
    const GAMBIO = 'gambio';
    const PRESTA = 'presta';
    const MODIFIED = 'modified';
    const OXID = 'oxid';
    const WOOCOMMERCE = 'woocommerce';
    
    /**
     * @var string[]
     */
    private static $shops = [
        self::SERVER,
        self::SHOPWARE,
        self::GAMBIO,
        self::PRESTA,
        self::MODIFIED,
        self::OXID,
        self::WOOCOMMERCE
    ];
    
    /**
     * @return string[]
     */
    public static function shops()
    {
        return self::$shops;
    }
}
