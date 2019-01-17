<?php

namespace Deployee\Plugins\DbTasks\Definitions;

use Deployee\Plugins\Deploy\Definitions\Parameter\ParameterCollection;
use Deployee\Plugins\Deploy\Definitions\Parameter\ParameterCollectionInterface;
use Deployee\Plugins\Deploy\Definitions\Tasks\TaskDefinitionInterface;

class SqlFileDefinition implements TaskDefinitionInterface
{
    /**
     * @var string
     */
    private $source;

    /**
     * @var bool
     */
    private $force;

    /**
     * @param string $source
     */
    public function __construct(string $source)
    {
        $this->source = $source;
        $this->force = false;
    }

    /**
     * @return $this
     */
    public function force(): self
    {
        $this->force = true;
        return $this;
    }

    /**
     * @return ParameterCollectionInterface
     */
    public function define(): ParameterCollectionInterface
    {
        return new ParameterCollection([
            'source' => $this->source,
            'force' => $this->force
        ]);
    }
}