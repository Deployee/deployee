<?php

namespace Deployee\Plugins\ShopwareTasks\Definitions;


use Deployee\Plugins\Deploy\Definitions\Parameter\ParameterCollection;
use Deployee\Plugins\Deploy\Definitions\Parameter\ParameterCollectionInterface;
use Deployee\Plugins\Deploy\Definitions\Tasks\TaskDefinitionInterface;

class ShopwareCommandDefinition implements TaskDefinitionInterface
{
    /**
     * @var string
     */
    private $command;

    /**
     * @var string
     */
    private $arguments;

    /**
     * ShopwareCommandDefinition constructor.
     * @param string $command
     * @param string $arguments
     */
    public function __construct(string $command, string $arguments = '')
    {
        $this->command = $command;
        $this->arguments = $arguments;
    }

    /**
     * @return ParameterCollectionInterface
     */
    public function define(): ParameterCollectionInterface
    {
        return new ParameterCollection(get_object_vars($this));
    }
}