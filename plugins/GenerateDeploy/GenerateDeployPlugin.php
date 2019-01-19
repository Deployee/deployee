<?php


namespace Deployee\Plugins\GenerateDeploy;


use Deployee\Components\Application\CommandCollection;
use Deployee\Components\Container\ContainerInterface;
use Deployee\Components\Plugins\PluginInterface;
use Deployee\Plugins\GenerateDeploy\Commands\GenerateDeployCommand;

class GenerateDeployPlugin implements PluginInterface
{
    public function boot(ContainerInterface $container)
    {

    }

    public function configure(ContainerInterface $container)
    {
        $container->extend(CommandCollection::class, function(CommandCollection $collection){
            $collection->addCommand(
                new GenerateDeployCommand()
            );

            return $collection;
        });
    }
}