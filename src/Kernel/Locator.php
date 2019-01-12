<?php

namespace Deployee\Kernel;

use Deployee\Kernel\Exceptions\ClassNotFoundException;
use Deployee\Kernel\Exceptions\ModuleNotFoundException;
use Deployee\Kernel\Modules\FacadeInterface;
use Deployee\Kernel\Modules\FactoryInterface;
use Deployee\Kernel\Modules\Module;
use Deployee\Kernel\Modules\ModuleCollection;
use Deployee\Kernel\Modules\ModuleInterface;


class Locator
{
    /**
     * @var DependencyProviderContainerInterface
     */
    private $dependencyProviderContainer;

    /**
     * @var ModuleCollection
     */
    private $modules;

    /**
     * @var array
     */
    private $namespaces;

    /**
     * @param DependencyProviderContainerInterface $dependencyProviderContainer
     * @param array $namespaces
     */
    public function __construct(DependencyProviderContainerInterface $dependencyProviderContainer, array $namespaces = [])
    {
        $this->dependencyProviderContainer = $dependencyProviderContainer;
        $this->modules = new ModuleCollection();
        $this->namespaces = $namespaces;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, $arguments): ModuleInterface
    {
        return $this->locate($name);
    }

    /**
     * @return DependencyProviderContainerInterface
     */
    public function getDependencyProviderContainer()
    {
        return $this->dependencyProviderContainer;
    }

    /**
     * @return array
     */
    public function getNamespaces()
    {
        return $this->namespaces;
    }

    public function locate(string $name): ModuleInterface
    {
        if(!$this->modules->hasModule($name)){
            $module = $this->createModule($name);
            $this->modules->addModule($name, $module);
            $module->onLoad();
        }

        return $this->modules->getModule($name);
    }

    /**
     * @param string $name
     * @return ModuleInterface
     * @throws ClassNotFoundException
     * @throws ModuleNotFoundException
     */
    private function createModule(string $name): ModuleInterface
    {
        try {
            $moduleClasses = ["{$name}\\{$name}Module", "{$name}\\Module"];
            $moduleClassName = $this->locateClassName($moduleClasses, 'Deployee\Kernel\Modules\ModuleInterface');
        }
        catch (ClassNotFoundException $e){
            $moduleClassName = Module::class;
        }

        /* @var ModuleInterface $module */
        if(!($module = new $moduleClassName) instanceof ModuleInterface){
            throw new ModuleNotFoundException("Invalid module class {$moduleClassName}");
        }

        /* @var FacadeInterface $facade */
        $facade = $this->createObjectImplementingInterface(
            ["{$name}\\{$name}Facade", "{$name}\\Facade"],
            FacadeInterface::class
        );

        /* @var FactoryInterface $factory */
        $factory = $this->createObjectImplementingInterface(
            ["{$name}\\{$name}Factory", "{$name}\\Factory"], FactoryInterface::class
        );

        $module->setFactory($factory);
        $module->setFacade($facade);
        $module->setLocator($this);

        return $module;
    }

    /**
     * @param array $classBaseNames
     * @param string $interface
     * @return object
     * @throws ClassNotFoundException
     */
    private function createObjectImplementingInterface(array $classBaseNames, string $interface)
    {
        $class = $this->locateClassName($classBaseNames, $interface);
        $object = new $class;
        $object->setLocator($this);

        return $object;
    }

    /**
     * @param array $classNames
     * @return string
     * @throws ClassNotFoundException
     */
    private function locateClassName(array $classNames, $mustBeSubClassOf = '')
    {
        foreach($this->namespaces as $namespace){
            foreach($classNames as $className) {
                if (class_exists($namespace . $className)
                    && ($mustBeSubClassOf === '' || is_subclass_of($namespace . $className, $mustBeSubClassOf))) {
                    return $namespace . $className;
                }
            }
        }

        throw new ClassNotFoundException("Could not locate \"".implode(', ', $classNames)."\"");
    }
}