<?php
namespace Jtl\Connector\Integrity\Models\Test;

class TestCollection
{
    /**
     * @var TestInterface[]
     */
    protected $tests = [];
    
    /**
     * @param TestInterface $test
     * @return $this
     */
    public function add(TestInterface $test)
    {
        $this->tests[$test->getSort()] = $test;
        return $this;
    }
    
    /**
     * @param TestInterface $test
     * @throws \Exception
     */
    public function removeByInstance(TestInterface $test)
    {
        $index = $test->getSort();
        if(!isset($this->tests[$index]) || !($this->tests[$index] === $test)) {
            throw new \Exception('This instance is not part of the collection and can\'t get removed!');
        }
        
        unset($this->tests[$index]);
    }
    
    /**
     * @param int $sort
     * @throws \Exception
     */
    public function removeBySort($sort)
    {
        if(!$this->has($sort)) {
            throw new \Exception(sprintf('The sort %s is not part of the collection!', $sort));
        }
        
        unset($this->tests[$sort]);
    }
    
    /**
     * @param int $sort
     * @return bool
     */
    public function has($sort)
    {
        return isset($this->tests[$sort]) && $this->tests[$sort] instanceof TestInterface;
    }
    
    /**
     * @param int $sort
     * @return TestInterface
     * @throws \Exception
     */
    public function get($sort)
    {
        if(!$this->has($sort)) {
            throw new \Exception(sprintf('No Test object with sort %s found!', $sort));
        }
        
        return $this->tests[$sort];
    }
    
    /**
     * @return int[]
     */
    public function getSorts()
    {
        return array_keys($this->tests);
    }
    
    /**
     * @param callable $callable - func(TestInterface $test)
     */
    public function walk(Callable $callable)
    {
        foreach ($this->tests as $test) {
            $callable($test);
        }
    }
    
    /**
     * @return TestInterface[]
     */
    public function toArray()
    {
        return array_values($this->tests);
    }
}
