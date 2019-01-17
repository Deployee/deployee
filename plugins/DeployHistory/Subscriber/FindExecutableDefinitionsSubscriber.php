<?php

namespace Deployee\Plugins\DeployHistory\Subscriber;

use Deployee\Components\DocBlock\DocBlock;
use Deployee\Components\Persistence\LazyPDO;
use Deployee\Plugins\Deploy\Events\FindExecutableDefinitionFilesEvent;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FindExecutableDefinitionsSubscriber implements EventSubscriberInterface
{
    /**
     * @var LazyPDO
     */
    private $lazyPdo;

    /**
     * @var DocBlock
     */
    private $docBlock;

    /**
     * @param LazyPDO $lazyPdo
     * @param DocBlock $docBlock
     */
    public function __construct(LazyPDO $lazyPdo, DocBlock $docBlock)
    {
        $this->lazyPdo = $lazyPdo;
        $this->docBlock = $docBlock;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FindExecutableDefinitionFilesEvent::class => 'onFindExecutableDefinitions'
        ];
    }

    /**
     * @param FindExecutableDefinitionFilesEvent $event
     * @throws \ReflectionException
     */
    public function onFindExecutableDefinitions(FindExecutableDefinitionFilesEvent $event)
    {
        $collection = $event->getDefinitionFileCollection();
        $executedClassNames = $this->getExecutedDefinitionClassNames();

        foreach($collection as $index => $class){
            if($this->docBlock->hasTag($class, 'runalways')
                || !in_array($class, $executedClassNames, false)){
                continue;
            }

            $event->getOutput()->writeln(
                sprintf('Deployment %s already executed. Skipping', $class),
                OutputInterface::VERBOSITY_DEBUG
            );

            $collection->offsetUnset($index);
        }
    }

    /**
     * @return string[]
     */
    private function getExecutedDefinitionClassNames(): array
    {
        $return = [];
        $sql = 'SELECT name FROM deployee_exec_history WHERE success = 1';
        $results = $this->lazyPdo->query($sql, \PDO::FETCH_ASSOC);

        foreach($results as $row){
            $return[] = $row['name'];
        }

        return $return;
    }
}