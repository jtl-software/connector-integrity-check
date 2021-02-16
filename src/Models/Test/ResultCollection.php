<?php
namespace Jtl\Connector\Integrity\Models\Test;

use Jtl\Connector\Integrity\Models\Collection\AbstractCollection;

class ResultCollection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $itemType = Result::class;
    
    /**
     * @return bool
     */
    public function useItemSorting()
    {
        return false;
    }
}
