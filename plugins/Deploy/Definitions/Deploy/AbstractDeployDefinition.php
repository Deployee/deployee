<?php


namespace Deployee\Plugins\Deploy\Definitions\Deploy;

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
     * @var TaskCreationHelper
     */
    private $taskCreationHelper;

    /**
     * @param TaskCreationHelper $taskCreationHelper
     */
    public function __construct(TaskCreationHelper $taskCreationHelper)
    {
        $this->taskCreationHelper = $taskCreationHelper;
        $this->tasks = new TaskDefinitionCollection();
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
        $task = $this->taskCreationHelper->createTaskDefinition($alias, $arguments);
        $this->addTaskDefinition($task);

        return $task;
    }
}