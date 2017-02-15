<?php
namespace Jtl\Connector\Integrity\Models\Test;

use Jtl\Connector\Integrity\Models\Collection\AbstractCollection;

class TestCollection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $item_type = AbstractTest::class;
    
    /**
     * @return bool
     */
    public function useItemSorting()
    {
        return true;
    }
}
