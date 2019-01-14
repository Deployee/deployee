<?php

namespace Deployee\Plugins\Deploy\Definitions\Tasks;

use Deployee\Components\Container\ContainerInterface;
use Deployee\Plugins\Deploy\Definitions\Parameter\ParameterCollectionInterface;

interface TaskDefinitionInterface
{
    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container);

    /**
     * @return ParameterCollectionInterface
     */
    public function define(): ParameterCollectionInterface;
}