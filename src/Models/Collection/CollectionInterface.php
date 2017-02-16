<?php
namespace Jtl\Connector\Integrity\Models\Collection;

interface CollectionInterface
{
    /**
     * @return bool
     */
    public function useItemSorting();
    
    /**
     * @param AbstractCollectionItem $item
     * @return self
     */
    public function add(AbstractCollectionItem $item);
    
    /**
     * @param AbstractCollectionItem $item
     * @throws \Exception
     */
    public function removeByInstance(AbstractCollectionItem $item);
    
    /**
     * @param int $index
     * @throws \Exception
     */
    public function removeByIndex($index);
    
    /**
     * @param int $sort
     * @throws \Exception
     */
    public function removeBySort($sort);
    
    /**
     * @param int $sort
     * @return bool
     */
    public function has($sort);
    
    /**
     * @param int $sort
     * @return AbstractCollectionItem
     * @throws \Exception
     */
    public function get($sort);
    
    /**
     * @return int[]
     */
    public function getSorts();
    
    /**
     * @return AbstractCollectionItem[]
     */
    public function getItems();
    
    /**
     * @param callable $callable - func(AbstractCollectionItem $item)
     */
    public function walk(Callable $callable);
    
    /**
     * @return AbstractCollectionItem[]
     */
    public function toArray();
}
