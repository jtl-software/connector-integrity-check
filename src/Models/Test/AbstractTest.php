<?php
namespace Jtl\Connector\Integrity\Models\Test;

use Jtl\Connector\Integrity\Models\Collection\AbstractCollectionItem;

abstract class AbstractTest extends AbstractCollectionItem implements TestInterface
{
    /**
     * @var ResultCollection
     */
    protected $results;
    
    public function __construct($sort = 0)
    {
        $this->results = new ResultCollection();
        parent::__construct($sort);
    }
    
    /**
     * @return ResultCollection
     */
    public function getResults()
    {
        return $this->results;
    }
    
    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'sort' => $this->sort,
            'results' => $this->results
        ];
    }
}
