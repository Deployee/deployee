<?php


namespace Deployee\Plugins\IdeSupport;


use Deployee\Components\Application\CommandCollection;
use Deployee\Components\Container\ContainerInterface;
use Deployee\Components\Plugins\PluginInterface;
use Deployee\Plugins\IdeSupport\Commands\UpdateIdeSupportCommand;

class IdeSupportPlugin implements PluginInterface
{
    public function boot(ContainerInterface $container)
    {
        // TODO: Implement boot() method.
    }

    public function configure(ContainerInterface $container)
    {
        /* @var CommandCollection $collection */
        $collection = $container->get(CommandCollection::class);
        $collection->addCommand(new UpdateIdeSupportCommand());
    }
}