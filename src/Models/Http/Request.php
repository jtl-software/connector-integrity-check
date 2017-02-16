<?php
namespace Jtl\Connector\Integrity\Models\Http;

use Jtl\Connector\Integrity\Models\Shop;

final class Request
{
    /**
     * @var string
     */
    private $shop;
    
    /**
     * @var int
     */
    private $number;
    
    public function __construct()
    {
        if (!isset($_GET['t']) && !isset($_GET['s'])) {
            header('HTTP/1.1 400 Bad Request');
            die();
        }
        
        if (!in_array($_GET['s'], Shop::shops())) {
            header('HTTP/1.1 406 Not Acceptable');
            die();
        }
        
        $this->shop = $_GET['s'];
        $this->number = (int) $_GET['t'];
    }
    
    /**
     * @return string
     */
    public function getShop()
    {
        return $this->shop;
    }
    
    /**
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }
}
