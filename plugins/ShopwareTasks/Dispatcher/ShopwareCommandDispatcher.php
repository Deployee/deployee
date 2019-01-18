<?php

namespace Deployee\Plugins\ShopwareTasks\Dispatcher;

use Deployee\Plugins\Deploy\Definitions\Tasks\TaskDefinitionInterface;
use Deployee\Plugins\Deploy\Dispatcher\AbstractTaskDefinitionDispatcher;
use Deployee\Plugins\Deploy\Dispatcher\DispatchResultInterface;
use Deployee\Plugins\ShellTasks\Definitions\ShellTaskDefinition;
use Deployee\Plugins\ShellTasks\Helper\ExecutableFinder;
use Deployee\Plugins\ShopwareTasks\Definitions\ShopwareCommandDefinition;

class ShopwareCommandDispatcher extends AbstractTaskDefinitionDispatcher
{
    /**
     * @var ExecutableFinder
     */
    private $execFinder;

    /**
     * @param ExecutableFinder $execFinder
     */
    public function __construct(ExecutableFinder $execFinder)
    {
        $this->execFinder = $execFinder;
    }

    /**
     * @param TaskDefinitionInterface $taskDefinition
     * @return bool
     */
    public function canDispatchTaskDefinition(TaskDefinitionInterface $taskDefinition): bool
    {
        return $taskDefinition instanceof ShopwareCommandDefinition;
    }

    /**
     * @param TaskDefinitionInterface $taskDefinition
     * @return DispatchResultInterface
     * @throws \Deployee\Plugins\Deploy\Exception\DispatcherException
     */
    public function dispatch(TaskDefinitionInterface $taskDefinition): DispatchResultInterface
    {
        $parameter = $taskDefinition->define();
        $shellTask = new ShellTaskDefinition('php');
        $shellTask->arguments(
            sprintf(
                '%s %s %s',
                $this->execFinder->find('swconsole'),
                $parameter->get('command'),
                $parameter->get('arguments')
            )
        );

        return $this->delegate($shellTask);
    }

}