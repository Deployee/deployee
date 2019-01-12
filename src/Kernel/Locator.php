<?php

namespace Deployee\Kernel;

use Deployee\Components\Container\ContainerInterface;
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
     * @var ContainerInterface
     */
    private $dependencyProviderContainer;

    /**
     * @var ModuleCollection
     */
    private $modules;

    /**
     * @var ModuleClassFinder
     */
    private $moduleClassFinder;

    /**
     * @param ContainerInterface $dependencyProviderContainer
     * @param array $namespaces
     */
    public function __construct(ContainerInterface $dependencyProviderContainer, array $namespaces = [])
    {
        $this->dependencyProviderContainer = $dependencyProviderContainer;
        $this->modules = new ModuleCollection();
        $this->moduleClassFinder = new ModuleClassFinder($namespaces);
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
     * @return ContainerInterface
     */
    public function getDependencyProviderContainer()
    {
        return $this->dependencyProviderContainer;
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
        $results = [];
        foreach($classNames as $className) {
            $results = array_merge(
                $this->moduleClassFinder->findClassImplementingInterface($className, $mustBeSubClassOf),
                $results
            );
        }
        if(count($results) === 0) {
            throw new ClassNotFoundException("Could not locate \"" . implode(', ', $classNames) . "\"");
        }

        return current($results);
    }
}