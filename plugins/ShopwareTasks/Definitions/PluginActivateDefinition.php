<?php

namespace Deployee\Plugins\ShopwareTasks\Definitions;

use Deployee\Plugins\Deploy\Definitions\Parameter\ParameterCollection;
use Deployee\Plugins\Deploy\Definitions\Parameter\ParameterCollectionInterface;
use Deployee\Plugins\Deploy\Definitions\Tasks\TaskDefinitionInterface;

class PluginActivateDefinition implements TaskDefinitionInterface
{
    /**
     * @var string
     */
    private $plugin;

    /**
     * PluginInstallDefinition constructor.
     * @param string $plugin
     */
    public function __construct(string $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * @return ParameterCollectionInterface
     */
    public function define(): ParameterCollectionInterface
    {
        return new ParameterCollection(get_object_vars($this));
    }
}