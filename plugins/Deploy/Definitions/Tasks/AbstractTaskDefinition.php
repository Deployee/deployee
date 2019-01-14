<?php

namespace Deployee\Plugins\Deploy\Definitions\Tasks;

use Deployee\Components\Container\ContainerInterface;

abstract class AbstractTaskDefinition implements TaskDefinitionInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }
}