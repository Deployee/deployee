<?php


namespace Deployee\Plugins;


use Deployee\Plugins\Locator\PluginLocator;
use Deployee\TestPlugin\TestPluginPlugin;
use PHPUnit\Framework\TestCase;

class PluginLocatorTest extends TestCase
{
    public function testLocatePlugins()
    {
        $locator = new PluginLocator();
        $plugins = $locator->locatePlugins();

        $this->assertTrue(is_array($plugins));
        $this->assertContains(TestPluginPlugin::class, $plugins);
    }
}