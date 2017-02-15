<?php
namespace Jtl\Connector\Integrity\Models\Test;

use Jtl\Connector\Integrity\Models\Collection\AbstractCollectionItem;

class Data extends AbstractCollectionItem
{
    /**
     * @var string
     */
    protected $message;
    
    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
    
    /**
     * @param string $message
     * @return Data
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
            'message' => $this->message
        ];
    }
}
