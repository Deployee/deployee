<?php

namespace Deployee\Plugins\Deploy\Definitions\Tasks;

interface TaskDefinitionCollectionInterface
{
    /**
     * @param TaskDefinitionInterface $task
     */
    public function addTaskDefinition(TaskDefinitionInterface $task);

    /**
     * @return TaskDefinitionInterface[]
     */
    public function toArray(): array;
}