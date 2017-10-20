<?php
namespace Jtl\Connector\Integrity\Shops\Server\Tests;

use Jtl\Connector\Integrity\Models\Test\AbstractTest;
use Jtl\Connector\Integrity\Models\Test\Data;
use Jtl\Connector\Integrity\Models\Test\Error;
use Jtl\Connector\Integrity\Models\Test\Result;

class OSTest extends AbstractTest
{
    private $uname_map = array(
        'CYGWIN_NT-5.1' => 'Windows',
        'Darwin' => 'Mac OS X',
        'IRIX64' => 'IRIX',
        'SunOS' => 'Solaris/OpenSolaris',
        'WIN32' => 'Windows',
        'WINNT' => 'Windows',
        'Windows NT' => 'Windows'
    );
    
    private $arch_map = array(
        'i386' => 'Intel x86',
        'i486' => 'Intel x86',
        'i586' => 'Intel x86',
        'i686' => 'Intel x86',
        'x86_64' => 'Intel x86_64',
        'sparc' => 'SPARC'
    );
    
    public function run()
    {
        $this->checkOS();
    }
    
    private function checkOS()
    {
        // Operating system
        $os = php_uname('s');
        if (array_key_exists($os, $this->uname_map)) {
            $os = $this->uname_map[$os];
        }
    
        // Processor architecture
        $arch = php_uname('m');
        if (array_key_exists($arch, $this->arch_map)) {
            $arch = $this->arch_map[$arch];
        }
        
        $result = (new Result())->setName('Operating System')
            ->setDescription('JTL-Software empfiehlt den Betrieb mit Linux-Webservern. Der Betrieb unter Solaris, FreeBSD oder Windows wird weder empfohlen noch unterstützt.')
            ->setData(
                (new Data())->setExpected('Linux')
                    ->setActual(sprintf('%s (%s)', $os, $arch))
            );
        
        if ($os !== 'Linux') {
            $result->setError(
                (new Error())->setMessage('Operating System nicht kompatibel')
                    ->setSolution('Bitte kontaktieren Sie Ihren Hoster oder Administrator')
            );
        }
    
        $this->getResults()->add($result);
    }
}
