<?php


namespace Deployee\Kernel;


use Composer\Autoload\ClassLoader;
use Deployee\ClassLoader\Module;
use PHPUnit\Framework\TestCase;

class LocatorTest extends TestCase
{
    /**
     * @var ClassLoader
     */
    private static $classLoader;

    public static function setUpBeforeClass()
    {
        static::$classLoader = require('vendor/autoload.php');
    }

    public function testLocate()
    {
        $locator = new Locator(
            new DependencyProviderContainer([
                Module::CLASS_LOADER_CONTAINER_ID => static::$classLoader
            ]),
            array_reverse(array_keys(static::$classLoader->getPrefixesPsr4()))
        );

        $classLoaderModule = $locator->locate('ClassLoader');
        $this->assertInstanceOf(Module::class, $classLoaderModule);
    }
}