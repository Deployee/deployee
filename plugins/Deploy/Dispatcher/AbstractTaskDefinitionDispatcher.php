<?php

namespace Deployee\Plugins\Deploy\Dispatcher;

use Deployee\Plugins\Deploy\Definitions\Tasks\TaskDefinitionInterface;

abstract class AbstractTaskDefinitionDispatcher implements TaskDefinitionDispatcherInterface
{
    /**
     * @var DispatcherFinder
     */
    protected $dispatcherFinder;

    /**
     * @param DispatcherFinder $dispatcherFinder
     */
    public function setDispatcherFinder(DispatcherFinder $dispatcherFinder)
    {
        $this->dispatcherFinder = $dispatcherFinder;
    }

    /**
     * @param TaskDefinitionInterface $taskDefinition
     * @return DispatchResultInterface
     * @throws \Deployee\Plugins\Deploy\Exception\DispatcherException
     */
    protected function delegate(TaskDefinitionInterface $taskDefinition): DispatchResultInterface
    {
        $dispatcher = $this->dispatcherFinder->findTaskDispatcherByDefinition($taskDefinition);
        return $dispatcher->dispatch($taskDefinition);
    }
}