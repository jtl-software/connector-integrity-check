<?php
namespace Jtl\Connector\Integrity\Models\Test;

abstract class AbstractTestLoader
{
    /**
     * @var TestCollection
     */
    protected $tests;
    
    /**
     * @param AbstractTest $test
     * @return self
     */
    public function addTest(AbstractTest $test)
    {
        $this->tests->add($test);
        return $this;
    }
    
    /**
     * @return TestCollection
     */
    public function getTests()
    {
        return $this->tests;
    }
}
