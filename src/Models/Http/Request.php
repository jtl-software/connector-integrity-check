<?php
namespace Jtl\Connector\Integrity\Models\Http;

use Jtl\Connector\Integrity\Models\Shop;

final class Request
{
    /**
     * @var string
     */
    private $shop = '';
    
    /**
     * @var int
     */
    private $number = 0;
    
    /**
     * @var string
     */
    private $action = '';
    
    public function __construct()
    {
        if (!isset($_GET['a'])) {
            header('HTTP/1.1 400 Bad Request');
            die();
        }
        
        if (isset($_GET['s']) && !in_array(strtolower($_GET['s']), Shop::shops())) {
            header('HTTP/1.1 406 Not Acceptable');
            die();
        }
        
        $this->shop = isset($_GET['s']) ? strtolower($_GET['s']) : '';
        $this->number = isset($_GET['t']) ? $_GET['t'] : 0;
        $this->action = $_GET['a'];
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
    
    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }
}
