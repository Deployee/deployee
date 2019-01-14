<?php


namespace Deployee\Plugins\Deploy\Definitions\Deploy;

use Deployee\Components\Container\ContainerInterface;
use Deployee\Plugins\Deploy\Definitions\Tasks\TaskDefinitionCollection;
use Deployee\Plugins\Deploy\Definitions\Tasks\TaskDefinitionCollectionInterface;
use Deployee\Plugins\Deploy\Definitions\Tasks\TaskDefinitionInterface;
use Deployee\Plugins\Deploy\Helper\TaskCreationHelper;

abstract class AbstractDeployDefinition implements DeployDefinitionInterface
{
    /**
     * @var TaskDefinitionCollectionInterface
     */
    private $tasks;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * AbstractDeployment constructor.
     */
    public function __construct()
    {
        $this->tasks = new TaskDefinitionCollection();
    }

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param TaskDefinitionInterface $task
     */
    public function addTaskDefinition(TaskDefinitionInterface $task)
    {
        $this->tasks->addTaskDefinition($task);
    }

    /**
     * @return TaskDefinitionCollectionInterface
     */
    public function getTaskDefinitions(): TaskDefinitionCollectionInterface
    {
        return $this->tasks;
    }

    /**
     * @param string $alias
     * @param array $arguments
     * @return TaskDefinitionInterface
     * @throws \ReflectionException
     */
    public function __call(string $alias, array $arguments): TaskDefinitionInterface
    {
        /* @var TaskCreationHelper $helper */
        $helper = $this->container->get(TaskCreationHelper::class);
        $task = $helper->createTaskDefinition($alias, $arguments);
        $this->addTaskDefinition($task);

        return $task;
    }


}