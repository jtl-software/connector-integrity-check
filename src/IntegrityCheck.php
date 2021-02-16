<?php
namespace Jtl\Connector\Integrity;

use Jtl\Connector\Integrity\Models\Http\Request;
use Jtl\Connector\Integrity\Models\Http\Response;
use Jtl\Connector\Integrity\Models\Shop;
use Jtl\Connector\Integrity\Models\Test\AbstractTest;
use Jtl\Connector\Integrity\Models\Test\Data;
use Jtl\Connector\Integrity\Models\Test\TestCollection;
use Jtl\Connector\Integrity\Shops\Server\ServerTestLoader;
use Jtl\Connector\Integrity\Shops\Shopware\ShopwareTestLoader;
use Jtl\Connector\Integrity\Shops\Gambio\GambioTestLoader;
use Jtl\Connector\Integrity\Shops\Modified\ModifiedTestLoader;
use Jtl\Connector\Integrity\Shops\WooCommerce\WooCommerceTestLoader;

final class IntegrityCheck
{
    const VERSION = '0.3';

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
            case 'get_shops':
                foreach (Shop::shops() as $shop) {
                    $response->getData()->add(
                        (new Data())->setValue(ucfirst($shop))
                    );
                }
                break;
            case 'get_sorts':
                if (empty($request->getShop())) {
                    throw new \InvalidArgumentException('Parameter s (shop) is required for action get_sorts');
                }
                
                foreach ($this->tests->getSorts() as $sort) {
                    $response->getData()->add(
                        (new Data())->setValue($sort)
                    );
                }
                break;
            case 'run_test':
                if (empty($request->getShop())) {
                    throw new \InvalidArgumentException('Parameter s (shop) is required for action run_test');
                }
                
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
        switch (Shop::normalize($shop)) {
            case Shop::SHOPWARE:
                $this->tests = (new ShopwareTestLoader())->getTests();
                break;
            case Shop::GAMBIO:
                $this->tests = (new GambioTestLoader())->getTests();
                break;
            case Shop::PRESTA:
                break;
            case Shop::MODIFIED:
                $this->tests = (new ModifiedTestLoader())->getTests();
                break;
            case Shop::OXID:
                break;
            case Shop::WOOCOMMERCE:
                $this->tests = (new WooCommerceTestLoader())->getTests();
                break;
        }
    
        $this->tests->merge((new ServerTestLoader())->getTests());
    }
}
