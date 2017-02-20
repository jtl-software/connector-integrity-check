<?php
namespace Jtl\Connector\Integrity\Shops\Server\Tests;

use Jtl\Connector\Integrity\Models\Test\AbstractTest;
use Jtl\Connector\Integrity\Models\Test\Data;
use Jtl\Connector\Integrity\Models\Test\Error;
use Jtl\Connector\Integrity\Models\Test\Result;

class PHPTest extends AbstractTest
{
    public function run()
    {
        /**
         * Base
         */
        $this->checkVersion();
        $this->checkMemoryLimit();
        $this->checkExecutionTime();
        $this->checkPostMaxSize();
        $this->checkUploadMaxFileSize();
        
        /**
         * Extensions
         */
        $this->checkJsonExtension();
        $this->checkPharExtension();
        $this->checkSQLite3Extension();
        $this->checkZipExtension();
    }
    
    private function shortHandToInt($shorthand)
    {
        switch (substr($shorthand, -1)) {
            case 'M':
            case 'm':
                return (int) $shorthand * 1048576;
            case 'K':
            case 'k':
                return (int) $shorthand * 1024;
            case 'G':
            case 'g':
                return (int) $shorthand * 1073741824;
            default:
                return $shorthand;
        }
    }
    
    /**
     * Version
     */
    private function checkVersion()
    {
        $result = (new Result())->setName('PHP-Version')
            ->setData(
                (new Data())->setExpected('>= 5.6.0')
                    ->setActual(PHP_VERSION)
            );
    
        if (version_compare(PHP_VERSION, '5.6.0', '<')) {
            $result->setError(
                (new Error())->setMessage('PHP-Version zu niedrig')
                    ->setSolution('Bitte kontaktieren Sie Ihren Hoster oder Administrator')
            );
        }
    
        $this->getResults()->add($result);
    }
    
    /**
     * Memory limit
     */
    private function checkMemoryLimit()
    {
        $result = (new Result())->setName('PHP memory_limit')
            ->setData(
                (new Data())->setExpected('>= 128M')
                    ->setActual(ini_get('memory_limit'))
            );
    
        if ($this->shortHandToInt(ini_get('memory_limit')) < $this->shortHandToInt('128M')) {
            $result->setError(
                (new Error())->setMessage('PHP memory_limit zu niedrig')
                    ->setSolution('Bitte kontaktieren Sie Ihren Hoster oder Administrator')
            );
        }
    
        $this->getResults()->add($result);
    }
    
    /**
     * Execution time
     */
    private function checkExecutionTime()
    {
        $result = (new Result())->setName('PHP max_execution_time')
            ->setData(
                (new Data())->setExpected('>= 120')
                    ->setActual(ini_get('max_execution_time'))
            );
        
        if (ini_get('max_execution_time') < 120) {
            $result->setError(
                (new Error())->setMessage('PHP max_execution_time zu niedrig')
                    ->setSolution('Bitte kontaktieren Sie Ihren Hoster oder Administrator')
            );
        }
        
        $this->getResults()->add($result);
    }
    
    /**
     * Post max size
     */
    private function checkPostMaxSize()
    {
        $result = (new Result())->setName('PHP post_max_size')
            ->setData(
                (new Data())->setExpected('>= 32M')
                    ->setActual(ini_get('post_max_size'))
            );
    
        if ($this->shortHandToInt(ini_get('post_max_size')) < $this->shortHandToInt('32M')) {
            $result->setError(
                (new Error())->setMessage('PHP post_max_size zu niedrig')
                    ->setSolution('Bitte kontaktieren Sie Ihren Hoster oder Administrator')
            );
        }
        
        $this->getResults()->add($result);
    }
    
    /**
     * Upload max filezise
     */
    private function checkUploadMaxFileSize()
    {
        $result = (new Result())->setName('PHP upload_max_filesize')
            ->setData(
                (new Data())->setExpected('>= 32M')
                    ->setActual(ini_get('upload_max_filesize'))
            );
        
        if ($this->shortHandToInt(ini_get('upload_max_filesize')) < $this->shortHandToInt('32M')) {
            $result->setError(
                (new Error())->setMessage('PHP upload_max_filesize zu niedrig')
                    ->setSolution('Bitte kontaktieren Sie Ihren Hoster oder Administrator')
            );
        }
        
        $this->getResults()->add($result);
    }
    
    /**
     * JSON Extension
     */
    private function checkJsonExtension()
    {
        $result = (new Result())->setName('PHP JSON-Extension');
    
        if (!function_exists('json_encode') || !function_exists('json_decode')) {
            $result->setError(
                (new Error())->setMessage('PHP JSON-Extension nicht vorhanden')
                    ->setSolution('Bitte kontaktieren Sie Ihren Hoster oder Administrator')
            );
        }
    
        $this->getResults()->add($result);
    }
    
    /**
     * Phar Extension
     */
    private function checkPharExtension()
    {
        $result = (new Result())->setName('PHP Phar-Extension');
    
        $check = true;
        if (!class_exists('Phar')) {
            $check = false;
        }
    
        if (extension_loaded('suhosin')) {
            $whitelist = explode(',', ini_get('suhosin.executor.include.whitelist'));
            if (!in_array('phar', $whitelist)) {
                $check = false;
            }
        }
        
        if (!$check) {
            $result->setError(
                (new Error())->setMessage('PHP Phar-Extension nicht vorhanden')
                    ->setSolution('Bitte kontaktieren Sie Ihren Hoster oder Administrator')
            );
        }
    
        $this->getResults()->add($result);
    }
    
    /**
     * SQLite3 Extension
     */
    private function checkSQLite3Extension()
    {
        $result = (new Result())->setName('PHP SQLite3-Extension');
        
        if (!class_exists('Sqlite3')) {
            $result->setError(
                (new Error())->setMessage('PHP SQLite3-Extension nicht vorhanden')
                    ->setSolution('Bitte kontaktieren Sie Ihren Hoster oder Administrator')
            );
        }
        
        $this->getResults()->add($result);
    }
    
    /**
     * Zip Extension
     */
    private function checkZipExtension()
    {
        $result = (new Result())->setName('PHP ZIP-Extension');
        
        if (!extension_loaded('zip') || !class_exists('ZipArchive')) {
            $result->setError(
                (new Error())->setMessage('PHP ZIP-Extension nicht vorhanden')
                    ->setSolution('Bitte kontaktieren Sie Ihren Hoster oder Administrator')
            );
        }
        
        $this->getResults()->add($result);
    }
}