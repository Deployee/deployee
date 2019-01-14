<?php


namespace Deployee\Plugins\Deploy\Definitions\Tasks;


use Deployee\Components\Container\ContainerInterface;

class TaskFactory
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $class
     * @param array $arguments
     * @return TaskDefinitionInterface
     * @throws \ReflectionException
     */
    public function createTask(string $class, array $arguments = []): TaskDefinitionInterface
    {
        $reflection = new \ReflectionClass($class);

        if(!$reflection->implementsInterface(TaskDefinitionInterface::class)){
            throw new \RuntimeException(sprintf('Invalid task definition class %s', $class));
        }

        /* @var TaskDefinitionInterface $taskDefinition */
        $taskDefinition = $reflection->getConstructor() && $reflection->getConstructor()->getNumberOfParameters() > 0
            ? $reflection->newInstanceArgs($arguments)
            : $reflection->newInstance();

        $taskDefinition->setContainer($this->container);

        return $taskDefinition;
    }
}