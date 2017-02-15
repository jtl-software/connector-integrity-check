<?php
namespace Jtl\Connector\Integrity\Models\Collection;

abstract class AbstractCollectionItem implements CollectionItemInterface, \JsonSerializable
{
    /**
     * @var int
     */
    protected $sort;
    
    /**
     * AbstractCollectionItem constructor.
     * @param int $sort
     */
    public function __construct($sort = 0)
    {
        if (!is_int($sort)) {
            throw new \InvalidArgumentException('Parameter sort must be of type integer');
        }
    
        $this->sort = $sort;
    }
    
    /**
     * @return int
     */
    public function getSort()
    {
        return $this->sort;
    }
}
