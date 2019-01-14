<?php

namespace Deployee\Plugins\Deploy\Events;

use Deployee\Plugins\Deploy\Definitions\Deploy\DeployDefinitionInterface;
use Symfony\Component\EventDispatcher\Event;

class PreDispatchDeploymentEvent extends Event
{
    /**
     * @var DeployDefinitionInterface
     */
    private $deployDefinition;

    /**
     * PreDispatchDeploymentEvent constructor.
     * @param DeployDefinitionInterface $deployDefinition
     */
    public function __construct(DeployDefinitionInterface $deployDefinition)
    {
        $this->deployDefinition = $deployDefinition;
    }

    /**
     * @return DeployDefinitionInterface
     */
    public function getDeployDefinition(): DeployDefinitionInterface
    {
        return $this->deployDefinition;
    }
}