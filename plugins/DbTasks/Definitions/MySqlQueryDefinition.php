<?php

namespace Deployee\Plugins\DbTasks\Definitions;

use Deployee\Deployment\Definitions\Parameter\ParameterCollection;
use Deployee\Deployment\Definitions\Tasks\AbstractTaskDefinition;

class MySqlQueryDefinition implements TaskDefinitionInterface
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
    public function __construct($query)
    {
        $this->query = $query;
        $this->force = false;
    }

    /**
     * @return $this
     */
    public function force()
    {
        $this->force = true;
        return $this;
    }

    /**
     * @return ParameterCollection
     */
    public function define()
    {
        return new ParameterCollection([
            'query' => $this->query,
            'force' => $this->force
        ]);
    }
}