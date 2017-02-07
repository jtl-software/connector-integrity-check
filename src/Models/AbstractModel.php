<?php
namespace Jtl\Connector\Integrity\Models;

abstract class AbstractModel
{
    /**
     * @return string
     */
    public function __toString()
    {
        return json_encode($this);
    }
}
