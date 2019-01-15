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

    }

    public function configure(ContainerInterface $container)
    {
        /* @var TaskCreationHelper $helper */
        $helper = $container->get(TaskCreationHelper::class);
        $helper->addAlias('directory', DirectoryTaskDefinition::class);
        $helper->addAlias('file', FileTaskDefinition::class);
    }
}