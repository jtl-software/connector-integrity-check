<?php
namespace Jtl\Connector\Integrity\Models\Collection;

use Traversable;

abstract class AbstractCollection implements CollectionInterface, \Countable,
    \JsonSerializable, \ArrayAccess, \IteratorAggregate
{
    /**
     * @var AbstractCollectionItem[]
     */
    protected $items = [];
    
    /**
     * @var string
     */
    protected $item_type = '';
    
    /**
     * @param AbstractCollectionItem $item
     * @return $this
     */
    public function add(AbstractCollectionItem $item)
    {
        $this->checkType($item);
    
        $this->useItemSorting() ?
            $this->items[$item->getSort()] = $item
            : $this->items[] = $item;
        
        return $this;
    }
    
    /**
     * @param AbstractCollectionItem $item
     * @throws \Exception
     */
    public function removeByInstance(AbstractCollectionItem $item)
    {
        $this->checkType($item);
        
        if ($this->useItemSorting()) {
            $index = $item->getSort();
            if (!isset($this->items[$index]) || !($this->items[$index] === $item)) {
                throw new \Exception('This instance is not part of the collection and can\'t get removed!');
            }
    
            unset($this->items[$index]);
        } else {
            $count = count($this->items);
            for ($i = 0; $i < $count; $i++) {
                if ($this->items[$i] === $item) {
                    unset($this->items[$i]);
                    $this->items = array_values($this->items);
                }
            }
        }
    }
    
    /**
     * @param int $index
     * @throws \Exception
     */
    public function removeByIndex($index)
    {
        $this->removeBySort($index);
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
        
        unset($this->items[$sort]);
    }
    
    /**
     * @param int $sort
     * @return bool
     */
    public function has($sort)
    {
        return isset($this->items[$sort]) && $this->items[$sort] instanceof AbstractCollectionItem;
    }
    
    /**
     * @param int $sort
     * @return AbstractCollectionItem
     * @throws \Exception
     */
    public function get($sort)
    {
        if(!$this->has($sort)) {
            throw new \Exception(sprintf('Object with sort %s could not be found!', $sort));
        }
        
        return $this->items[$sort];
    }
    
    /**
     * @return int[]
     */
    public function getSorts()
    {
        return array_keys($this->items);
    }
    
    /**
     * @return AbstractCollectionItem[]
     */
    public function getItems()
    {
        return $this->items;
    }
    
    /**
     * @param callable $callable - func(AbstractCollectionItem $item)
     */
    public function walk(Callable $callable)
    {
        foreach ($this->items as $item) {
            $callable($item);
        }
    }
    
    /**
     * @param AbstractCollection $collection
     */
    public function merge(AbstractCollection $collection)
    {
        foreach ($collection->getItems() as $item) {
            $this->items[$item->getSort()] = $item;
        }
        
        usort($this->items, function(AbstractCollectionItem $a, AbstractCollectionItem $b) {
            if ($a->getSort() == $b->getSort()) {
                return 0;
            }
            
            return $a->getSort() < $b->getSort() ? -1 : 1;
        });
    }
    
    /**
     * @return AbstractCollectionItem[]
     */
    public function toArray()
    {
        return array_values($this->items);
    }
    
    /**
     * @param AbstractCollectionItem $item
     * @throws \InvalidArgumentException
     */
    protected function checkType(AbstractCollectionItem $item)
    {
        if (!empty($this->item_type) && !($item instanceof $this->item_type)) {
            throw new \InvalidArgumentException(sprintf(
                'Parameter item must be from type %s - %s given',
                $this->item_type,
                get_class($item)
            ));
        }
    }
    
    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }
    
    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return isset($this->items[$offset]) ? $this->items[$offset] : null;
    }
    
    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        is_null($offset) ?
            $this->items[] = $value :
            $this->items[$offset] = $value;
    }
    
    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }
    
    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        return $this->items;
    }
    
    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return count($this->items) > 0;
    }
    
    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }
}
