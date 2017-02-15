<?php
namespace Jtl\Connector\Integrity\Models\Http;

use Jtl\Connector\Integrity\Models\AbstractModel;
use Jtl\Connector\Integrity\Models\Test\ResultCollection;

class Response extends AbstractModel
{
    /**
     * @var ResultCollection[]
     */
    protected $results = [];
    
    /**
     * @return ResultCollection[]
     */
    public function getResults()
    {
        return $this->results;
    }
    
    /**
     * @param ResultCollection $collection
     * @return self
     */
    public function addResults(ResultCollection $collection)
    {
        $this->results[] = $collection;
        return $this;
    }
    
    public function out()
    {
        header('Content-Type: application/json');
        echo json_encode($this->results);
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
            'results' => json_encode($this->results)
        ];
    }
}
