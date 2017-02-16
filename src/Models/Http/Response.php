<?php
namespace Jtl\Connector\Integrity\Models\Http;

use Jtl\Connector\Integrity\Models\AbstractModel;
use Jtl\Connector\Integrity\Models\Test\DataCollection;
use Jtl\Connector\Integrity\Models\Test\ResultCollection;

class Response extends AbstractModel
{
    /**
     * @var DataCollection
     */
    protected $data;
    
    /**
     * @var ResultCollection
     */
    protected $results;
    
    /**
     * Response constructor.
     */
    public function __construct()
    {
        $this->data = new DataCollection();
        $this->results = new ResultCollection();
    }
    
    /**
     * @return DataCollection
     */
    public function getData()
    {
        return $this->data;
    }
    
    /**
     * @return ResultCollection
     */
    public function getResults()
    {
        return $this->results;
    }
    
    /**
     * @param DataCollection $collection
     * @return self
     */
    public function setData(DataCollection $data)
    {
        $this->data = $data;
        return $this;
    }
    
    /**
     * @param ResultCollection $collection
     * @return self
     */
    public function setResults(ResultCollection $collection)
    {
        $this->results = $collection;
        return $this;
    }
    
    public function out()
    {
        header('Content-Type: application/json');
        echo json_encode($this->jsonSerialize());
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
            'data' => $this->data,
            'results' => $this->results
        ];
    }
}
