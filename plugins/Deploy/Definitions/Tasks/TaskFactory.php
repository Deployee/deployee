<?php


namespace Deployee\Plugins\Deploy\Definitions\Tasks;

use Deployee\Components\Dependency\ContainerResolver;

class TaskFactory
{
    /**
     * @var ContainerResolver
     */
    private $resolver;

    /**
     * @param ContainerResolver $resolver
     */
    public function __construct(ContainerResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * @param string $class
     * @param array $arguments
     * @return TaskDefinitionInterface
     * @throws \ReflectionException
     */
    public function createTask(string $class, array $arguments = []): TaskDefinitionInterface
    {
        /* @var TaskDefinitionInterface $taskDefinition */
        $taskDefinition = $this->resolver->createInstance($class, $arguments);
        return $taskDefinition;
    }
}