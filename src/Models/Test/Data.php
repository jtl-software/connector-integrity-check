<?php
namespace Jtl\Connector\Integrity\Models\Test;

use Jtl\Connector\Integrity\Models\Collection\AbstractCollectionItem;

class Data extends AbstractCollectionItem
{
    /**
     * @var mixed
     */
    protected $value;
    
    /**
     * @var string
     */
    protected $expected = '';
    
    /**
     * @var string
     */
    protected $actual = '';
    
    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
    
    /**
     * @param mixed $value
     * @return Data
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getExpected()
    {
        return $this->expected;
    }
    
    /**
     * @param string $expected
     * @return Data
     */
    public function setExpected($expected)
    {
        if (!is_string($expected)) {
            throw new \InvalidArgumentException('Parameter expected must be a string');
        }
        
        $this->expected = $expected;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getActual()
    {
        return $this->actual;
    }
    
    /**
     * @param string $actual
     * @return Data
     */
    public function setActual($actual)
    {
        if (!is_string($actual)) {
            throw new \InvalidArgumentException('Parameter actual must be a string');
        }
        
        $this->actual = $actual;
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
            'value' => $this->value,
            'expected' => $this->expected,
            'actual' => $this->actual
        ];
    }
}
