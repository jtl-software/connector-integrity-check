<?php
namespace Jtl\Connector\Integrity\Models\Test;

interface TestInterface
{
    public function run();
    
    /**
     * @return ResultCollection
     */
    public function getResults();
}
