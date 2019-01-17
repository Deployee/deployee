<?php


namespace Deployee\Plugins\DbTasks;


use Deployee\Components\Container\ContainerInterface;
use Deployee\Components\Dependency\ContainerResolver;
use Deployee\Components\Plugins\PluginInterface;
use Deployee\Plugins\DbTasks\Definitions\SqlFileDefinition;
use Deployee\Plugins\DbTasks\Definitions\SqlQueryDefinition;
use Deployee\Plugins\DbTasks\Dispatcher\SqlFileDispatcher;
use Deployee\Plugins\DbTasks\Dispatcher\SqlQueryDispatcher;
use Deployee\Plugins\Deploy\Dispatcher\DispatcherCollection;
use Deployee\Plugins\Deploy\Helper\TaskCreationHelper;

class DbTasksPlugin implements PluginInterface
{
    public function boot(ContainerInterface $container)
    {

    }

    /**
     * @param ContainerInterface $container
     * @throws \ReflectionException
     */
    public function configure(ContainerInterface $container)
    {
        /* @var TaskCreationHelper $helper */
        $helper = $container->get(TaskCreationHelper::class);
        $helper->addAlias('sqlFile', SqlFileDefinition::class);
        $helper->addAlias('sqlQuery', SqlQueryDefinition::class);

        /* @var DispatcherCollection $dispatcherCollection */
        $dispatcherCollection = $container->get(DispatcherCollection::class);
        /* @var ContainerResolver $resolver */
        $resolver = $container->get(ContainerResolver::class);

        $dispatcherArray = [
            $resolver->createInstance(SqlFileDispatcher::class),
            $resolver->createInstance(SqlQueryDispatcher::class),
        ];

        $dispatcherCollection->addDispatcherArray($dispatcherArray);
    }
}