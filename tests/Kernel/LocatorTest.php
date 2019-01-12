<?php


namespace Deployee\Kernel;

use Deployee\ClassLoader\Module;
use Deployee\LocatorTestCase;

class LocatorTest extends LocatorTestCase
{
    public function testLocate()
    {
        $classLoaderModule = $this->getAppLocator()->locate('ClassLoader');
        $this->assertInstanceOf(Module::class, $classLoaderModule);
    }
}