<?php
namespace Jtl\Connector\Integrity\Models\Test;

use Jtl\Connector\Integrity\Models\Collection\AbstractCollection;

class TestCollection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $itemType = AbstractTest::class;
    
    /**
     * @return bool
     */
    public function useItemSorting()
    {
        return true;
    }
}
