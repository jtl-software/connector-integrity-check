<?php
namespace Jtl\Connector\Integrity\Models\Test;

use Jtl\Connector\Integrity\Models\Collection\AbstractCollectionItem;

class Error extends AbstractCollectionItem
{
    const LEVEL_CRITICAL = 1;
    const LEVEL_ERROR = 2;
    const LEVEL_WARNING = 3;
    
    /**
     * @var string
     */
    protected $message;
    
    /**
     * @var string
     */
    protected $solution;
    
    /**
     * @var int
     */
    protected $level = self::LEVEL_ERROR;
    
    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
    
    /**
     * @param string $message
     * @return Error
     */
    public function setMessage($message)
    {
        if (!is_string($message)) {
            throw new \InvalidArgumentException('Parameter message must be a string');
        }
        
        $this->message = $message;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getSolution()
    {
        return $this->solution;
    }
    
    /**
     * @param string $solution
     * @return Error
     */
    public function setSolution($solution)
    {
        if (!is_string($solution)) {
            throw new \InvalidArgumentException('Parameter solution must be a string');
        }
        
        $this->solution = $solution;
        return $this;
    }
    
    /**
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }
    
    /**
     * @param int $level
     * @return Error
     */
    public function setLevel($level)
    {
        if (!is_int($level)) {
            throw new \InvalidArgumentException('Parameter level must be an integer');
        }
        
        $this->level = $level;
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
            'message' => $this->message,
            'solution' => $this->solution,
            'level' => $this->level
        ];
    }
}
