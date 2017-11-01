<?php

namespace Deployee\Plugins\RunDeploy\Dispatcher;


use Deployee\Deployment\Definitions\Tasks\TaskDefinitionInterface;
use Deployee\Kernel\Locator;
use Deployee\Kernel\LocatorAwareInterface;
use Deployee\Plugins\RunDeploy\Module;

class DispatcherFinder
{
    /**
     * @var Locator
     */
    private $locator;

    /**
     * DispatcherFinder constructor.
     * @param Locator $locator
     */
    public function __construct(Locator $locator)
    {
        $this->locator = $locator;
    }

    /**
     * @param TaskDefinitionInterface $taskDefinition
     * @return TaskDefinitionDispatcherInterface
     */
    public function findTaskDispatcherByDefinition(TaskDefinitionInterface $taskDefinition)
    {
        /* @var TaskDefinitionDispatcherInterface $dispatcher */
        foreach($this->locator->Dependency()->getDependency(Module::DISPATCHER_COLLECTION_DEPENDENCY) as $dispatcher){
            if($dispatcher->canDispatchTaskDefinition($taskDefinition)){
                return $dispatcher;
            }
        }

        throw new \RuntimeException(sprintf("No dispatcher found for %s task", get_class($taskDefinition)));
    }
}