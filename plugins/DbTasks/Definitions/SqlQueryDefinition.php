<?php

namespace Deployee\Plugins\DbTasks\Definitions;

use Deployee\Plugins\Deploy\Definitions\Parameter\ParameterCollection;
use Deployee\Plugins\Deploy\Definitions\Parameter\ParameterCollectionInterface;
use Deployee\Plugins\Deploy\Definitions\Tasks\TaskDefinitionInterface;

class SqlQueryDefinition implements TaskDefinitionInterface
{
    /**
     * @var string
     */
    private $query;

    /**
     * @var bool
     */
    private $force;

    /**
     * MySqlDumpTask constructor.
     * @param string $query
     */
    public function __construct(string $query)
    {
        $this->query = $query;
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
            'query' => $this->query,
            'force' => $this->force
        ]);
    }
}