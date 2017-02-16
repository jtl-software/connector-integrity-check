<?php
namespace Jtl\Connector\Integrity\Models\Test;

class TestType implements \JsonSerializable
{
    const DATABASE = 'db';
    const REQUIREMENTS = 'requirements';
    const FILES = 'files';
    
    /**
     * @var string[]
     */
    protected $types = [
        self::DATABASE,
        self::REQUIREMENTS,
        self::FILES
    ];
    
    /**
     * @var string
     */
    protected $type;
    
    /**
     * TestType constructor.
     * @param string $type - constant
     */
    public function __construct($type)
    {
        $this->checkType($type);
        
        $this->type = $type;
    }
    
    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * @param string $type
     * @return TestType
     */
    public function setType($type)
    {
        $this->checkType($type);
        
        $this->type = $type;
        return $this;
    }
    
    /**
     * @param string $type
     * @throws \InvalidArgumentException
     */
    protected function checkType($type)
    {
        if (!in_array($type, $this->types)) {
            throw new \InvalidArgumentException('Invalid type constant');
        }
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
            'type' => $this->type
        ];
    }
}
