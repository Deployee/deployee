<?php

namespace Deployee\Plugins\ShopwareTasks\Definitions;


use Deployee\Plugins\Deploy\Definitions\Parameter\ParameterCollection;
use Deployee\Plugins\Deploy\Definitions\Parameter\ParameterCollectionInterface;
use Deployee\Plugins\Deploy\Definitions\Tasks\TaskDefinitionInterface;

class PluginUninstallDefinition implements TaskDefinitionInterface
{
    /**
     * @var string
     */
    private $plugin;

    /**
     * @var bool
     */
    private $secure;

    /**
     * PluginReinstallDefinition constructor.
     * @param string $plugin
     * @param bool $secure
     */
    public function __construct(string $plugin, bool $secure = false)
    {
        $this->plugin = $plugin;
        $this->secure = $secure;
    }


    /**
     * @param bool $secure
     * @return $this
     */
    public function secure(bool $secure = true): self
    {
        $this->secure = $secure;
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