<?php

namespace Deployee\Plugins\ShopwareTasks\Definitions;

use Deployee\Plugins\Deploy\Definitions\Parameter\ParameterCollection;
use Deployee\Plugins\Deploy\Definitions\Parameter\ParameterCollectionInterface;
use Deployee\Plugins\Deploy\Definitions\Tasks\TaskDefinitionInterface;

class PluginReinstallDefinition implements TaskDefinitionInterface
{
    /**
     * @var string
     */
    private $plugin;

    /**
     * @var bool
     */
    private $removedata;

    /**
     * PluginReinstallDefinition constructor.
     * @param string $plugin
     * @param bool $removedata
     */
    public function __construct(string $plugin, bool $removedata = false)
    {
        $this->plugin = $plugin;
        $this->removedata = $removedata;
    }


    /**
     * @param bool $removedata
     * @return $this
     */
    public function removedata(bool $removedata = true): self
    {
        $this->removedata = $removedata;
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