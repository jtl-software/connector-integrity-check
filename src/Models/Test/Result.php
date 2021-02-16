<?php
namespace Jtl\Connector\Integrity\Models\Test;

use Jtl\Connector\Integrity\Models\Collection\AbstractCollectionItem;

class Result extends AbstractCollectionItem
{
    /**
     * @var string
     */
    protected $name = '';
    
    /**
     * @var string
     */
    protected $description = '';
    
    /**
     * @var bool
     */
    protected $hasError = false;
    
    /**
     * @var Error
     */
    protected $error;
    
    /**
     * @var Data
     */
    protected $data;
    
    public function __construct($sort = 0)
    {
        parent::__construct($sort);
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * @param string $name
     * @return Result
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * @param string $description
     * @return Result
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
    
    /**
     * @return bool
     */
    public function hasError()
    {
        return $this->hasError;
    }
    
    /**
     * @return Error
     */
    public function getError()
    {
        return $this->error;
    }
    
    /**
     * @param Error $error
     * @return Result
     */
    public function setError($error)
    {
        if (!($error instanceof Error)) {
            throw new \InvalidArgumentException(sprintf(
                'Expected parameter error to be %s, got %s',
                Error::class,
                is_object($error) ? get_class($error) : gettype($error)
            ));
        }
        
        $this->error = $error;
        $this->hasError = !is_null($error);
        return $this;
    }
    
    /**
     * @return Data
     */
    public function getData()
    {
        return $this->data;
    }
    
    /**
     * @param Data $data
     * @return Result
     */
    public function setData($data)
    {
        if (!($data instanceof Data)) {
            throw new \InvalidArgumentException(sprintf(
                'Expected parameter data to be %s, got %s',
                Data::class,
                is_object($data) ? get_class($data) : gettype($data)
            ));
        }
        
        $this->data = $data;
        return $this;
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
            'name' => $this->name,
            'description' => $this->description,
            'data' => $this->data,
            'error' => $this->error,
            'has_error' => $this->hasError
        ];
    }
}
