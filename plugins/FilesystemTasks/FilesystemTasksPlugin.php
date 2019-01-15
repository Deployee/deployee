<?php


namespace Deployee\Plugins\FilesystemTasks;

use Deployee\Components\Container\ContainerInterface;
use Deployee\Components\Dependency\ContainerResolver;
use Deployee\Components\Plugins\PluginInterface;
use Deployee\Plugins\Deploy\Dispatcher\DispatcherCollection;
use Deployee\Plugins\Deploy\Helper\TaskCreationHelper;
use Deployee\Plugins\FilesystemTasks\Definitions\DirectoryTaskDefinition;
use Deployee\Plugins\FilesystemTasks\Definitions\FileTaskDefinition;
use Deployee\Plugins\FilesystemTasks\Dispatcher\DirectoryTaskDefinitionDispatcher;
use Deployee\Plugins\FilesystemTasks\Dispatcher\FileTaskDefinitionDispatcher;
use Deployee\Plugins\FilesystemTasks\Dispatcher\PermissionsTaskDefinitionDispatcher;

class FilesystemTasksPlugin implements PluginInterface
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
        $helper->addAlias('directory', DirectoryTaskDefinition::class);
        $helper->addAlias('file', FileTaskDefinition::class);

        /* @var DispatcherCollection $dispatcherCollection */
        $dispatcherCollection = $container->get(DispatcherCollection::class);
        /* @var ContainerResolver $resolver */
        $resolver = $container->get(ContainerResolver::class);

        $dispatcherArray = [
            $resolver->createInstance(DirectoryTaskDefinitionDispatcher::class),
            $resolver->createInstance(FileTaskDefinitionDispatcher::class),
            $resolver->createInstance(PermissionsTaskDefinitionDispatcher::class)
        ];

        $dispatcherCollection->addDispatcherArray($dispatcherArray);
    }
}