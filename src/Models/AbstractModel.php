<?php
namespace Jtl\Connector\Integrity\Models;

abstract class AbstractModel implements \JsonSerializable
{
    /**
     * @return string
     */
    public function __toString()
    {
        return json_encode($this);
    }
}
