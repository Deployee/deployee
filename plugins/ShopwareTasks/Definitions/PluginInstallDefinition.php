<?php

namespace Deployee\Plugins\ShopwareTasks\Definitions;


use Deployee\Plugins\Deploy\Definitions\Parameter\ParameterCollection;
use Deployee\Plugins\Deploy\Definitions\Parameter\ParameterCollectionInterface;
use Deployee\Plugins\Deploy\Definitions\Tasks\TaskDefinitionInterface;

class PluginInstallDefinition implements TaskDefinitionInterface
{
    /**
     * @var string
     */
    private $plugin;

    /**
     * @var bool
     */
    private $activate;

    /**
     * PluginInstallDefinition constructor.
     * @param string $plugin
     * @param bool $activate
     */
    public function __construct(string $plugin, bool $activate = false)
    {
        $this->plugin = $plugin;
        $this->activate = $activate;
    }

    /**
     * @param bool $activate
     * @return $this
     */
    public function activate(bool $activate = true): self
    {
        $this->activate = $activate;
        return $this;
    }

    /**
     * @return ParameterCollectionInterface
     */
    public function define(): ParameterCollectionInterface
    {
        return new ParameterCollection(get_object_vars($this));
    }
}