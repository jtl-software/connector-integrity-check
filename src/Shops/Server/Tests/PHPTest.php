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
//        $this->checkSapi();
        $this->checkVersion();
        $this->checkMemoryLimit();
        $this->checkExecutionTime();
        $this->checkPostMaxSize();
        $this->checkUploadMaxFileSize();
        $this->checkSafeMode();
        $this->checkTempDir();
        
        /**
         * Extensions
         */
        $this->checkJsonExtension();
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
     * Sapi
     */
    private function checkSapi()
    {
        $sapi = PHP_SAPI;
        $sapi_names = array(
            'apache' => 'Apache',
            'apache2filter' => 'Apache 2.0',
            'apache2handler' => 'Apache 2.0',
            'cgi' => 'CGI',
            'cgi-fcgi' => 'FastCGI',
            'fpm' => 'FPM'
        );
    
        if (function_exists('fastcgi_finish_request')) {
            $sapi = 'fpm';
        }
    
        $result = (new Result())->setName('PHP-SAPI')
            ->setData(
                (new Data())->setExpected('Apache2, FastCGI, FPM')
                    ->setActual($sapi_names[$sapi])
            );
        
        if (!in_array($sapi, array('apache', 'apache2filter', 'apache2handler', 'cgi-fcgi', 'fpm'))) {
            $result->setError(
                (new Error())->setMessage('PHP-SAPI falsch')
                    ->setSolution('Bitte kontaktieren Sie Ihren Hoster oder Administrator')
            );
        }
        
        $this->getResults()->add($result);
    }
    
    /**
     * Version
     */
    private function checkVersion()
    {
        $result = (new Result())->setName('PHP-Version')
            ->setData(
                (new Data())->setExpected('>= 7.1.3')
                    ->setActual(PHP_VERSION)
            );
    
        if (version_compare(PHP_VERSION, '7.1.3', '<')) {
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
        
        if (ini_get('max_execution_time') < 120 && ini_get('max_execution_time') != 0 ) {
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
     * Safe mode
     */
    private function checkSafeMode()
    {
        $safe_mode = (bool) ini_get('safe_mode');
        
        $result = (new Result())->setName('PHP safe_mode')
            ->setData(
                (new Data())->setExpected('off')
                    ->setActual($safe_mode ? 'on' : 'off')
            );
        
        if ($safe_mode) {
            $result->setError(
                (new Error())->setMessage('PHP safe_mode aktiv')
                    ->setSolution('Bitte kontaktieren Sie Ihren Hoster oder Administrator')
            );
        }
        
        $this->getResults()->add($result);
    }
    
    /**
     * Temp directory
     */
    private function checkTempDir()
    {
        $temp_dir = sys_get_temp_dir();
        $writeable = is_writable($temp_dir);
        
        $result = (new Result())->setName('PHP writable_temp_dir')
            ->setDescription(sprintf(
                'Das temporäre Verzeichnis (<code>%s</code>), das für PHP konfiguriert wurde, sollte beschreibbar sein',
                $temp_dir
            ))
            ->setData(
                (new Data())->setExpected('ja')
                    ->setActual($writeable ? 'ja' : 'nein')
            );
        
        if (!$writeable) {
            $result->setError(
                (new Error())->setMessage('PHP writable_temp_dir ')
                    ->setSolution('Bitte kontaktieren Sie Ihren Hoster oder Administrator')
                    ->setLevel(Error::LEVEL_WARNING)
            );
        }
        
        $this->getResults()->add($result);
    }
    
    /**
     * JSON Extension
     */
    private function checkJsonExtension()
    {
        $result = (new Result())->setName('PHP JSON-Extension')
            ->setDescription('JTL-Connector benötigt PHP-Unterstützung für das JSON-Format.<br>In neueren Debian-PHP-Paketen wird die Unterstützung für JSON standardmäßig nicht mehr mitinstalliert. Hierfür ist die Installation des Pakets <code>php-json</code> erforderlich.');
    
        if (!function_exists('json_encode') || !function_exists('json_decode')) {
            $result->setError(
                (new Error())->setMessage('PHP JSON-Extension nicht vorhanden')
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
        $result = (new Result())->setName('PHP SQLite3-Extension')
            ->setDescription('JTL-Connector benötigt PHP-Unterstützung für SQLite3.<br>
Hierfür ist unter Debian z.B. die Installation des Pakets <code>php-sqlite</code> erforderlich.');
        
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
        $result = (new Result())->setName('PHP ZIP-Extension')
            ->setDescription('JTL-Connector benötigt PHP-Unterstützung für die Erstellung von ZIP-Archiven.<br>
Dies kann z.B. durch Eingabe von <code>pecl install zip</code> erreicht werden.');
        
        if (!extension_loaded('zip') || !class_exists('ZipArchive')) {
            $result->setError(
                (new Error())->setMessage('PHP ZIP-Extension nicht vorhanden')
                    ->setSolution('Bitte kontaktieren Sie Ihren Hoster oder Administrator')
            );
        }
        
        $this->getResults()->add($result);
    }
}
