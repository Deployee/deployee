<?php

namespace Deployee\Plugins\Deploy\Helper;

use Deployee\Plugins\Deploy\Definitions\Tasks\TaskDefinitionInterface;
use Deployee\Plugins\Deploy\Definitions\Tasks\TaskFactory;

class TaskCreationHelper
{
    /**
     * @var TaskFactory
     */
    private $factory;

    /**
     * @var array
     */
    private $alias;

    /**
     * @param TaskFactory $factory
     */
    public function __construct(TaskFactory $factory)
    {
        $this->alias = [];
        $this->factory = $factory;
    }

    /**
     * @param string $alias
     * @param string $class
     */
    public function addAlias(string $alias, string $class)
    {
        $this->alias[strtolower($alias)] = ["alias" => $alias, "class" => $class];
    }

    /**
     * @return string[]
     */
    public function getAllAlias(): array
    {
        return $this->alias;
    }

    /**
     * @param string $alias
     * @param array $arguments
     * @return TaskDefinitionInterface
     * @throws \ReflectionException
     */
    public function createTaskDefinition(string $alias, array $arguments = []): TaskDefinitionInterface
    {
        $class = $this->alias[strtolower($alias)]['class'] ?? $alias;
        return $this->factory->createTask($class, $arguments);
    }
}