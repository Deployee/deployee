<?php


namespace Deployee\Plugins\Install;


use Deployee\Components\Application\CommandCollection;
use Deployee\Components\Container\ContainerInterface;
use Deployee\Components\Plugins\PluginInterface;
use Deployee\Plugins\Install\Commands\InstallCommand;

class InstallPlugin implements PluginInterface
{
    public function boot(ContainerInterface $container)
    {

    }

    public function configure(ContainerInterface $container)
    {
        /* @var CommandCollection $commandCollection */
        $commandCollection = $container->get(CommandCollection::class);
        $commandCollection->addCommand(new InstallCommand());
    }
}