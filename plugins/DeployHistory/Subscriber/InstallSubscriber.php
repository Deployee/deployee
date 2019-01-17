<?php


namespace Deployee\Plugins\DeployHistory\Subscriber;

use Deployee\Components\Persistence\LazyPDO;
use Deployee\Plugins\Install\Events\RunInstallCommandEvent;
use Deployee\Plugins\MySqlTasks\Definitions\MySqlFileDefinition;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class InstallSubscriber implements EventSubscriberInterface
{
    /**
     * @var LazyPDO $lazyPdo
     */
    private $lazyPdo;

    /**
     * @param LazyPDO $lazyPdo
     */
    public function __construct(LazyPDO $lazyPdo)
    {
        $this->lazyPdo = $lazyPdo;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            RunInstallCommandEvent::class => 'onInstall'
        ];
    }

    /**
     * @throws \PDOException
     */
    public function onInstall()
    {
        $installSql = file_get_contents(__DIR__ . '/../install/install.sql');
        $this->lazyPdo->exec($installSql);
    }
}