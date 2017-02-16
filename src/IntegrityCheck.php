<?php
namespace Jtl\Connector\Integrity;

use Jtl\Connector\Integrity\Models\Http\Request;
use Jtl\Connector\Integrity\Models\Http\Response;
use Jtl\Connector\Integrity\Models\Shop;
use Jtl\Connector\Integrity\Models\Test\AbstractTest;
use Jtl\Connector\Integrity\Models\Test\Data;
use Jtl\Connector\Integrity\Models\Test\ResultCollection;
use Jtl\Connector\Integrity\Models\Test\TestCollection;
use Jtl\Connector\Integrity\Shops\Shopware\ShopwareTestLoader;
use Jtl\Connector\Integrity\Shops\WooCommerce\WooCommerceTestLoader;

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
        $response = $this->route($request);
    
        $response->out();
    }
    
    /**
     * @param Request $request
     * @return Response
     */
    protected function route(Request $request)
    {
        $response = new Response();
        switch ($request->getAction()) {
            case 'get_tests':
                $sorts = $this->tests->getSorts();
                foreach ($sorts as $sort) {
                    $response->getData()->add(
                        (new Data())->setValue($sort)
                    );
                }
                break;
            case 'run_test':
                /** @var AbstractTest $test */
                $test = $this->tests->get($request->getNumber());
                $test->run();
    
                $response->setResults($test->getResults());
                break;
        }
        
        return $response;
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
                $this->tests = (new WooCommerceTestLoader())->getTests();
                break;
        }
    }
}
