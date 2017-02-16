<?php
namespace Jtl\Connector\Integrity\Models;

final class Shop
{
    const SHOPWARE = 'Shopware';
    const GAMBIO = 'Gambio';
    const PRESTA = 'Presta';
    const MODIFIED = 'Modified';
    const OXID = 'Oxid';
    const WOOCOMMERCE = 'WooCommerce';
    
    /**
     * @var string[]
     */
    private static $shops = [
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
    public static function shops($toLower = false)
    {
        if ($toLower) {
            return array_map(function($shop) {
                return strtolower($shop);
            }, self::$shops);
        }
        
        return self::$shops;
    }
    
    /**
     * @param string $shop
     * @return string
     */
    public static function normalize($shop)
    {
        switch (strtolower($shop)) {
            case 'shopware': return self::SHOPWARE;
            case 'gambio': return self::GAMBIO;
            case 'presta': return self::PRESTA;
            case 'modified': return self::MODIFIED;
            case 'oxid': return self::OXID;
            case 'woocommerce': return self::WOOCOMMERCE;
        }
    }
}
