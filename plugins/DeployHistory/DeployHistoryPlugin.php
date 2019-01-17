<?php


namespace Deployee\Plugins\DeployHistory;


use Deployee\Components\Container\ContainerInterface;
use Deployee\Components\Persistence\LazyPDO;
use Deployee\Components\Plugins\PluginInterface;
use Deployee\Plugins\DeployHistory\Subscriber\InstallSubscriber;
use Symfony\Component\EventDispatcher\EventDispatcher;

class DeployHistoryPlugin implements PluginInterface
{
    public function boot(ContainerInterface $container)
    {

    }

    public function configure(ContainerInterface $container)
    {
        /* @var LazyPDO $lazyPdo */
        $lazyPdo = $container->get(LazyPDO::class);

        /* @var EventDispatcher $eventDispatcher */
        $eventDispatcher = $container->get(EventDispatcher::class);
        $eventDispatcher->addSubscriber(new InstallSubscriber($lazyPdo));
    }

}