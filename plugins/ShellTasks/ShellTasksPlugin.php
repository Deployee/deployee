<?php


namespace Deployee\Plugins\ShellTasks;


use Deployee\Components\Container\ContainerInterface;
use Deployee\Components\Dependency\ContainerResolver;
use Deployee\Components\Plugins\PluginInterface;
use Deployee\Plugins\Deploy\Dispatcher\DispatcherCollection;
use Deployee\Plugins\Deploy\Helper\TaskCreationHelper;
use Deployee\Plugins\ShellTasks\Definitions\ShellTaskDefinition;
use Deployee\Plugins\ShellTasks\Dispatcher\ShellTaskDefinitionDispatcher;
use Deployee\Plugins\ShellTasks\Helper\ExecutableFinder;

class ShellTasksPlugin implements PluginInterface
{
    /**
     * @param ContainerInterface $container
     */
    public function boot(ContainerInterface $container)
    {
        $container->set(ExecutableFinder::class, function(){
            return new ExecutableFinder();
        });
    }

    /**
     * @param ContainerInterface $container
     * @throws \ReflectionException
     */
    public function configure(ContainerInterface $container)
    {
        /* @var TaskCreationHelper $taskCreationHelper */
        $taskCreationHelper = $container->get(TaskCreationHelper::class);
        $taskCreationHelper->addAlias('shell', ShellTaskDefinition::class);

        /* @var ContainerResolver $resolver */
        $resolver = $container->get(ContainerResolver::class);

        /* @var ShellTaskDefinitionDispatcher $taskDispatcher */
        $taskDispatcher = $resolver->createInstance(ShellTaskDefinitionDispatcher::class);

        /* @var DispatcherCollection $dispatcherCollection */
        $dispatcherCollection = $container->get(DispatcherCollection::class);
        $dispatcherCollection->addDispatcher($taskDispatcher);
    }

}