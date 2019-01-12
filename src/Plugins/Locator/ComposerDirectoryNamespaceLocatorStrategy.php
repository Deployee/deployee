<?php


namespace Deployee\Plugins\Locator;


use Composer\Autoload\ClassLoader;
use Deployee\Plugins\PluginInterface;

class ComposerDirectoryNamespaceLocatorStrategy implements LocatorStrategyInterface
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

    /**
     * @return array
     */
    public function locate(): array
    {
        $list = [];

        foreach($this->classLoader->getPrefixesPsr4() as $namespace => $rootDirs){
            foreach($rootDirs as $rootDir){
                $list = array_merge($list, $this->locateInDirectory($namespace, $rootDir));
            }
        }

        return $list;
    }

    /**
     * @param string $rootNamespace
     * @param string $rootDir
     * @return array
     */
    private function locateInDirectory(string $rootNamespace, string $rootDir): array
    {
        $list = [];
        foreach(new \DirectoryIterator($rootDir) as $iterator){
            if($iterator->isFile() || $iterator->isDot()){
                continue;
            }

            $expectedNamespace = $rootNamespace . $iterator->getBasename() . '\\';
            if($this->isPluginNamespace($expectedNamespace)){
                $list[] = $expectedNamespace . basename($expectedNamespace) . 'Plugin';
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