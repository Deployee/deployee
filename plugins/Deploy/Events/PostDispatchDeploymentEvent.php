<?php

namespace Deployee\Plugins\Deploy\Events;



use Deployee\Plugins\Deploy\Definitions\Deploy\DeployDefinitionInterface;
use Symfony\Component\EventDispatcher\Event;

class PostDispatchDeploymentEvent extends Event
{
    /**
     * @var DeployDefinitionInterface
     */
    private $deployDefinition;

    /**
     * @var bool
     */
    private $success;

    /**
     * PostDispatchDeploymentEvent constructor.
     * @param DeployDefinitionInterface $deployDefinition
     * @param bool $success
     */
    public function __construct(DeployDefinitionInterface $deployDefinition, bool $success)
    {
        $this->deployDefinition = $deployDefinition;
        $this->success = $success;
    }

    /**
     * @return DeployDefinitionInterface
     */
    public function getDeployDefinition(): DeployDefinitionInterface
    {
        return $this->deployDefinition;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }
}