<?php

namespace Deployee\Plugins\Deploy\Definitions\Tasks;


class TaskDefinitionCollection implements TaskDefinitionCollectionInterface
{
    /**
     * @var array
     */
    private $tasks;

    /**
     * TaskDefinitionCollection constructor.
     */
    public function __construct()
    {
        $this->tasks = [];
    }

    /**
     * @param TaskDefinitionInterface $task
     */
    public function addTaskDefinition(TaskDefinitionInterface $task)
    {
        $this->tasks[] = $task;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->tasks;
    }
}