<?php


namespace Deployee\Plugins\Deploy\Definitions\Deploy;


use Deployee\Components\Container\ContainerInterface;
use Deployee\Plugins\Deploy\Definitions\Tasks\TaskDefinitionCollectionInterface;
use Deployee\Plugins\Deploy\Definitions\Tasks\TaskDefinitionInterface;

interface DeployDefinitionInterface
{
    /**
     * @return void
     */
    public function define();

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container);

    /**
     * @param TaskDefinitionInterface $task
     */
    public function addTaskDefinition(TaskDefinitionInterface $task);

    /**
     * @return TaskDefinitionCollectionInterface
     */
    public function getTaskDefinitions(): TaskDefinitionCollectionInterface;
}