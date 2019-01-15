<?php


namespace Deployee\Plugins\FilesystemTasks\Dispatcher;


use Deployee\Plugins\Deploy\Definitions\Tasks\TaskDefinitionInterface;
use Deployee\Plugins\Deploy\Dispatcher\DispatchResult;
use Deployee\Plugins\Deploy\Dispatcher\DispatchResultInterface;
use Deployee\Plugins\Deploy\Dispatcher\TaskDefinitionDispatcherInterface;
use Deployee\Plugins\FilesystemTasks\Definitions\PermissionsTaskDefinition;


class PermissionsTaskDefinitionDispatcher implements TaskDefinitionDispatcherInterface
{
    /**
     * @param TaskDefinitionInterface $taskDefinition
     * @return bool
     */
    public function canDispatchTaskDefinition(TaskDefinitionInterface $taskDefinition): bool
    {
        return $taskDefinition instanceof PermissionsTaskDefinition;
    }

    /**
     * @TODO: Implement dispatching permission task
     * @param TaskDefinitionInterface $taskDefinition
     * @return DispatchResult
     */
    public function dispatch(TaskDefinitionInterface $taskDefinition): DispatchResultInterface
    {
        return new DispatchResult(0, '', '');
    }
}