<?php


namespace Deployee\Plugins\DeployHistory;


use Deployee\Components\Container\ContainerInterface;
use Deployee\Components\DocBlock\DocBlock;
use Deployee\Components\Persistence\LazyPDO;
use Deployee\Components\Plugins\PluginInterface;
use Deployee\Plugins\DeployHistory\Subscriber\FindExecutableDefinitionsSubscriber;
use Deployee\Plugins\DeployHistory\Subscriber\InstallSubscriber;
use Deployee\Plugins\DeployHistory\Subscriber\PostDispatchDeploymentSubscriber;
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
        $eventDispatcher->addSubscriber(new FindExecutableDefinitionsSubscriber($lazyPdo, new DocBlock()));
        $eventDispatcher->addSubscriber(new PostDispatchDeploymentSubscriber($lazyPdo));
    }

}