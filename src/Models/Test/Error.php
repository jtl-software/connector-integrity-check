<?php
namespace Jtl\Connector\Integrity\Models\Test;

use Jtl\Connector\Integrity\Models\Collection\AbstractCollectionItem;

class Error extends AbstractCollectionItem
{
    /**
     * @var int
     */
    protected $code;
    
    /**
     * @var string
     */
    protected $message;
    
    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }
    
    /**
     * @param int $code
     * @return Error
     * @throws \InvalidArgumentException
     */
    public function setCode($code)
    {
        if (!is_int($code)) {
            throw new \InvalidArgumentException('Parameter code must be an integer');
        }
        
        $this->code = $code;
        return $this;
    }
    
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
     * @throws \InvalidArgumentException
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
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'code' => $this->code,
            'message' => $this->message
        ];
    }
}
