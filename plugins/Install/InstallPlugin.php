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
        /* @var CommandCollection $collection */
        $collection = $container->get(CommandCollection::class);
        $collection->addCommand(new InstallCommand());
    }

    public function run(ContainerInterface $container)
    {

    }
}