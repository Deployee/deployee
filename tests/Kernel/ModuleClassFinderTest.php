<?php


namespace Deployee\Kernel;


use Deployee\Kernel\Modules\Module;
use Deployee\Kernel\Modules\ModuleInterface;
use PHPUnit\Framework\TestCase;

class ModuleClassFinderTest extends TestCase
{
    public function testFindClass()
    {
        $finder = new ModuleClassFinder(['Deployee\\']);
        $result = $finder->findClass('Kernel\\ModuleClassFinder');

        $this->assertIsArray($result);
        $this->assertSame(1, count($result));
        $this->assertSame(ModuleClassFinder::class, current($result));
    }

    public function testFindClassNoResult()
    {
        $finder = new ModuleClassFinder(['Deployee\\']);
        $result = $finder->findClass('Kernel\\DoesNotExist');

        $this->assertIsArray($result);
        $this->assertSame(0, count($result));
    }

    public function testFindClassImplementingInterface()
    {
        $finder = new ModuleClassFinder(['Deployee\\']);
        $result = $finder->findClassImplementingInterface('Kernel\\Modules\\Module', ModuleInterface::class);

        $this->assertIsArray($result);
        $this->assertSame(1, count($result));
        $this->assertSame(Module::class, current($result));
        $this->assertContains(ModuleInterface::class, class_implements(current($result)));
    }
}