<?php
namespace Jtl\Connector\Integrity\Models\Collection;

interface CollectionItemInterface
{
    /**
     * CollectionItemInterface constructor.
     * @param int $sort
     */
    public function __construct($sort = 0);
    
    /**
     * @return int
     */
    public function getSort();
}
