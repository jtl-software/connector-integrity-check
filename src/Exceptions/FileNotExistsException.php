<?php
namespace Jtl\Connector\Integrity\Exceptions;

class FileNotExistsException extends \Exception
{
    /**
     * @var string
     */
    protected $missing_file = '';
    
    /**
     * @return string
     */
    public function getMissingFile()
    {
        return $this->missing_file;
    }
    
    /**
     * @param string $missing_file
     * @return FileNotExistsException
     */
    public function setMissingFile($missing_file)
    {
        $this->missing_file = $missing_file;
        return $this;
    }
}
