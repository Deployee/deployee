<?php

namespace Deployee\Plugins\DeployHistory\Subscriber;


use Deployee\Components\Persistence\LazyPDO;
use Deployee\Plugins\Deploy\Events\PostDispatchDeploymentEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PostDispatchDeploymentSubscriber implements EventSubscriberInterface
{
    /**
     * @var LazyPDO
     */
    private $lazyPdo;

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            PostDispatchDeploymentEvent::class => 'onPostDispatchDeployment'
        ];
    }

    /**
     * @param LazyPDO $lazyPdo
     */
    public function __construct(LazyPDO $lazyPdo)
    {
        $this->lazyPdo = $lazyPdo;
    }

    /**
     * @param PostDispatchDeploymentEvent $event
     */
    public function onPostDispatchDeployment(PostDispatchDeploymentEvent $event){

        $statement = $this->lazyPdo->prepare(
            'INSERT INTO deployee_exec_history (`name`, `success`) VALUES(:name, :success)'
        );

        $statement->execute([
            ':name' => get_class($event->getDeployDefinition()),
            ':success' => $event->isSuccess() ? 1 : 0
        ]);
    }
}