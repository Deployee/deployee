<?php


namespace Deployee\Plugins\Deploy;


use Deployee\Components\Application\CommandCollection;
use Deployee\Components\Container\ContainerInterface;
use Deployee\Components\Plugins\PluginInterface;
use Deployee\Plugins\Deploy\Commands\InstallCommand;

class DeployPlugin implements PluginInterface
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