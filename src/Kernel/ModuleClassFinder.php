<?php


namespace Deployee\Kernel;


class ModuleClassFinder
{
    /**
     * @var string[]
     */
    private $namespaces;

    /**
     * ModuleClassFinder constructor.
     * @param string[] $namespaces
     */
    public function __construct(array $namespaces)
    {
        $this->namespaces = $namespaces;
    }

    public function findClassImplementingInterface(string $classBaseName, string $interface): array
    {
        return array_filter(
            $this->findClass($classBaseName),
            function($class) use($interface){
                return in_array($interface, class_implements($class), false);
            }
        );
    }

    public function findClass(string $classBaseName): array
    {
        $results = [];
        foreach($this->namespaces as $namespace){
            $expectedClass = $namespace . $classBaseName;
            if(class_exists($expectedClass)){
                $results[] = $expectedClass;
            }
        }

        return $results;
    }
}