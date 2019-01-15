<?php

namespace Deployee\Plugins\Deploy\Dispatcher;

use Deployee\Components\Container\ContainerInterface;
use Deployee\Plugins\Deploy\Definitions\Tasks\TaskDefinitionInterface;
use Deployee\Plugins\Deploy\Exception\DispatcherException;
use Deployee\Plugins\RunDeploy\Module;

class DispatcherFinder
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }


    /**
     * @param TaskDefinitionInterface $taskDefinition
     * @return TaskDefinitionDispatcherInterface
     * @throws DispatcherException
     */
    public function findTaskDispatcherByDefinition(TaskDefinitionInterface $taskDefinition): TaskDefinitionDispatcherInterface
    {
        /* @var DispatcherCollection $dispatcherCollection */
        $dispatcherCollection = $this->container->get(DispatcherCollection::class);

        foreach($dispatcherCollection->toArray() as $dispatcher){
            if($dispatcher->canDispatchTaskDefinition($taskDefinition) === true){
                return $dispatcher;
            }
        }

        throw new DispatcherException(sprintf('No dispatcher found for %s task', get_class($taskDefinition)));
    }
}