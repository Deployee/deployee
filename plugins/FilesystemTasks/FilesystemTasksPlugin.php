<?php


namespace Deployee\Plugins\FilesystemTasks;


use Deployee\Components\Container\ContainerInterface;
use Deployee\Components\Plugins\PluginInterface;
use Deployee\Plugins\Deploy\Helper\TaskCreationHelper;
use Deployee\Plugins\FilesystemTasks\Definitions\DirectoryTaskDefinition;
use Deployee\Plugins\FilesystemTasks\Definitions\FileTaskDefinition;

class FilesystemTasksPlugin implements PluginInterface
{
    public function boot(ContainerInterface $container)
    {
        /* @var TaskCreationHelper $taskHelper */
        $helper = $container->get(TaskCreationHelper::class);
        $helper->addAlias('directory', DirectoryTaskDefinition::class);
        $helper->addAlias('file', FileTaskDefinition::class);
    }

    public function run(ContainerInterface $container)
    {

    }

}