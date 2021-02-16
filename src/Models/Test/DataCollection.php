<?php
namespace Jtl\Connector\Integrity\Models\Test;

use Jtl\Connector\Integrity\Models\Collection\AbstractCollection;

class DataCollection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $itemType = Data::class;
    
    /**
     * @return bool
     */
    public function useItemSorting()
    {
        return false;
    }
}
