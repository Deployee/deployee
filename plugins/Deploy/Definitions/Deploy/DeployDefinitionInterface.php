<?php


namespace Deployee\Plugins\Deploy\Definitions\Deploy;

use Deployee\Plugins\Deploy\Definitions\Tasks\TaskDefinitionCollectionInterface;
use Deployee\Plugins\Deploy\Definitions\Tasks\TaskDefinitionInterface;

interface DeployDefinitionInterface
{
    /**
     * @return void
     */
    public function define();

    /**
     * @param TaskDefinitionInterface $task
     */
    public function addTaskDefinition(TaskDefinitionInterface $task);

    /**
     * @return TaskDefinitionCollectionInterface
     */
    public function getTaskDefinitions(): TaskDefinitionCollectionInterface;
}