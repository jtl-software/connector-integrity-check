<?php
namespace Jtl\Connector\Integrity;

use Jtl\Connector\Integrity\Models\Http\Response;
use Jtl\Connector\Integrity\Models\Test\AbstractTest;
use Jtl\Connector\Integrity\Models\Test\TestCollection;

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
        $response = new Response();
        foreach ($this->tests as $test) {
            $test->run();
            $response->addResults($test->getResults());
        }
    
        $response->out();
    }
}
