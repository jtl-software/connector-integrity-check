<?php
namespace Jtl\Connector\Integrity\Models\Test;

use Jtl\Connector\Integrity\Models\Collection\AbstractCollection;

class ErrorCollection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $itemType = Error::class;
    
    /**
     * @return bool
     */
    public function useItemSorting()
    {
        return false;
    }
}
