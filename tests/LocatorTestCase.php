<?php


namespace Deployee;



use Composer\Autoload\ClassLoader;
use Deployee\ClassLoader\Module;
use Deployee\Kernel\DependencyProviderContainer;
use Deployee\Kernel\Locator;
use PHPUnit\Framework\TestCase;

class LocatorTestCase extends TestCase
{
    /**
     * @var ClassLoader
     */
    private static $classLoader;

    /**
     * @var Locator
     */
    private static $locator;

    public static function setUpBeforeClass()
    {
        self::$classLoader = require('vendor/autoload.php');
        self::$locator = new Locator(
            new DependencyProviderContainer([
                Module::CLASS_LOADER_CONTAINER_ID => self::$classLoader
            ]),
            array_reverse(array_keys(self::$classLoader->getPrefixesPsr4()))
        );
    }

    protected function getAppLocator(): Locator
    {
        return self::$locator;
    }
}