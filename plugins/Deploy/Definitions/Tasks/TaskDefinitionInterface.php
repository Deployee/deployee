<?php

namespace Deployee\Plugins\Deploy\Definitions\Tasks;

use Deployee\Plugins\Deploy\Definitions\Parameter\ParameterCollectionInterface;

interface TaskDefinitionInterface
{
    /**
     * @return ParameterCollectionInterface
     */
    public function define(): ParameterCollectionInterface;
}