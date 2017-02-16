<?php
namespace Jtl\Connector\Integrity;

use Jtl\Connector\Integrity\Models\Http\Request;
use Jtl\Connector\Integrity\Models\Http\Response;
use Jtl\Connector\Integrity\Models\Shop;
use Jtl\Connector\Integrity\Models\Test\AbstractTest;
use Jtl\Connector\Integrity\Models\Test\TestCollection;
use Jtl\Connector\Integrity\Shops\Shopware\ShopwareTestLoader;

final class IntegrityCheck
{
    /**
     * @var TestCollection
     */
    protected $tests;
    
    protected static $instance;
    
    protected function __construct()
    {
        $this->tests = new TestCollection();
    }
    
    private function __clone() { }
    
    /**
     * @return IntegrityCheck
     */
    public static function init()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
    
    /**
     * @param AbstractTest $test
     * @return IntegrityCheck
     */
    public function registerTest(AbstractTest $test)
    {
        $this->tests->add($test);
        return $this;
    }
    
    /**
     * @param TestInterface[]
     * @return IntegrityCheck
     * @throws \InvalidArgumentException
     */
    public function registerTests(array $tests)
    {
        $this->tests = new TestCollection();
        foreach ($tests as $test) {
            if (!($test instanceof AbstractTest)) {
                throw new \InvalidArgumentException(sprintf(
                    'Some element is not an instance of %s',
                    AbstractTest::class
                ));
            }
            
            $this->tests->add($test);
        }
        
        return $this;
    }
    
    public function run()
    {
        $request = new Request();
        
        $this->switchScope($request->getShop());
        
        $response = new Response();
        foreach ($this->tests as $test) {
            $test->run();
            $response->addResults($test->getResults());
        }
    
        $response->out();
    }
    
    /**
     * @param string $shop
     */
    protected function switchScope($shop)
    {
        switch ($shop) {
            case Shop::SHOPWARE:
                $this->tests = (new ShopwareTestLoader())->getTests();
                break;
            case Shop::GAMBIO:
                break;
            case Shop::PRESTA:
                break;
            case Shop::MODIFIED:
                break;
            case Shop::OXID:
                break;
            case Shop::WOOCOMMERCE:
                break;
        }
    }
}
