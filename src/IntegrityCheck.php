<?php
namespace Jtl\Connector\Integrity;

use Jtl\Connector\Integrity\Models\Test\TestCollection;
use Jtl\Connector\Integrity\Models\Test\TestInterface;

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
     * @param TestInterface $test
     * @return IntegrityCheck
     */
    public function registerTest(TestInterface $test)
    {
        $this->tests->add($test);
        return $this;
    }
    
    /**
     * @param TestInterface[]
     * @return IntegrityCheck
     */
    public function registerTests(array $tests)
    {
        $this->tests = new TestCollection();
        foreach ($tests as $test) {
            if (!($test instanceof TestInterface)) {
                throw new \InvalidArgumentException('Some element is not an instance of TestInterface');
            }
            
            $this->tests->add($test);
        }
        
        return $this;
    }
    
    public function run()
    {
        
    }
}
