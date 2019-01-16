<?php

namespace Deployee\Plugins\ShellTasks\Definitions;

use Deployee\Plugins\Deploy\Definitions\Parameter\ParameterCollection;
use Deployee\Plugins\Deploy\Definitions\Parameter\ParameterCollectionInterface;
use Deployee\Plugins\Deploy\Definitions\Tasks\TaskDefinitionInterface;

class ShellTaskDefinition implements TaskDefinitionInterface
{
    /**
     * @var ParameterCollection
     */
    private $parameter;

    /**
     * DirectoryTask constructor.
     * @param string $executable
     */
    public function __construct($executable)
    {
        $this->parameter = new ParameterCollection([
            'executable' => $executable
        ]);
    }

    /**
     * @param string $arguments
     * @return $this
     */
    public function arguments($arguments): self
    {
        $this->parameter->set('arguments', $arguments);
        return $this;
    }

    /**
     * @return ParameterCollection
     */
    public function define(): ParameterCollectionInterface
    {
        return $this->parameter;
    }
}