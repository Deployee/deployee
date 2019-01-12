<?php


namespace Deployee\Plugins\Locator;


use Composer\Autoload\ClassLoader;
use Deployee\Plugins\PluginInterface;

class ComposerNamespaceLocatorStrategy implements LocatorStrategyInterface
{
    /**
     * @var ClassLoader
     */
    private $classLoader;

    /**
     * ComposerNamespaceLocatorStrategy constructor.
     * @param ClassLoader $classLoader
     */
    public function __construct(ClassLoader $classLoader)
    {
        $this->classLoader = $classLoader;
    }

    public function locate(): array
    {
        $list = [];
        foreach($this->classLoader->getPrefixesPsr4() as $namespace => $rootDirs){
            if($this->isPluginNamespace($namespace)){
                $list[] = $namespace . basename($namespace) . 'Plugin';
            }
        }

        return $list;
    }

    /**
     * @param string $namespace
     * @return bool
     */
    private function isPluginNamespace(string $namespace): bool
    {
        $expectedClass = $namespace . basename($namespace) . 'Plugin';
        return class_exists($expectedClass)
            && in_array(PluginInterface::class, class_implements($expectedClass), false);
    }
}