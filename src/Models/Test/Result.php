<?php
namespace Jtl\Connector\Integrity\Models\Test;

use Jtl\Connector\Integrity\Models\Collection\AbstractCollectionItem;

class Result extends AbstractCollectionItem
{
    /**
     * @var TestType
     */
    protected $type;
    
    /**
     * @var ErrorCollection
     */
    protected $errors;
    
    /**
     * @var DataCollection
     */
    protected $data;
    
    public function __construct($sort = 0)
    {
        $this->errors = new ErrorCollection();
        $this->data = new DataCollection();
        $this->type = new TestType(TestType::DATABASE);
        
        parent::__construct($sort);
    }
    
    /**
     * @return TestType
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * @param TestType $type
     * @return Result
     * @throws \InvalidArgumentException
     */
    public function setType($type)
    {
        if (!($type instanceof TestType)) {
            throw new \InvalidArgumentException(sprintf('Parameter type must be an instance of %s', TestType::class));
        }
        
        $this->type = $type;
        return $this;
    }
    
    /**
     * @return ErrorCollection
     */
    public function getErrors()
    {
        return $this->errors;
    }
    
    /**
     * @param Error $error
     * @return self
     */
    public function addError(Error $error)
    {
        $this->errors->add($error);
        return $this;
    }
    
    /**
     * @return bool
     */
    public function hasErrors()
    {
        return count($this->errors) > 0;
    }
    
    /**
     * @return DataCollection
     */
    public function getData()
    {
        return $this->data;
    }
    
    /**
     * @param Data $data
     * @return self
     */
    public function addData(Data $data)
    {
        $this->data->add($data);
        return $this;
    }
    
    /**
     * @return bool
     */
    public function hasData()
    {
        return count($this->data) > 0;
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
            'type' => $this->type->getType(),
            'data' => $this->data,
            'errors' => $this->errors
        ];
    }
}
