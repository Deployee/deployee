<?php

namespace Deployee\Plugins\FilesystemTasks\Definitions;

use Deployee\Plugins\Deploy\Definitions\Parameter\ParameterCollection;
use Deployee\Plugins\Deploy\Definitions\Parameter\ParameterCollectionInterface;
use Deployee\Plugins\Deploy\Definitions\Tasks\TaskDefinitionInterface;

class PermissionsTaskDefinition implements TaskDefinitionInterface
{
    /**
     * @var ParameterCollection
     */
    private $parameter;

    /**
     * DirectoryTask constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->parameter = new ParameterCollection([
            'path' => $path
        ]);
    }

    /**
     * @param int $permissions
     * @return $this
     */
    public function permissions($permissions): self
    {
        $this->parameter->set('permissions', $permissions);
        return $this;
    }

    /**
     * @param string $owner
     * @return $this
     */
    public function owner($owner): self
    {
        $this->parameter->set('owner', $owner);
        return $this;
    }

    /**
     * @param string $group
     * @return $this
     */
    public function group($group): self
    {
        $this->parameter->set('group', $group);
        return $this;
    }

    /**
     * @param bool $recursive
     * @return $this
     */
    public function recursive(): self
    {
        $this->parameter->set('recursive', true);
        return $this;
    }

    /**
     * @return ParameterCollectionInterface
     */
    public function define(): ParameterCollectionInterface
    {
        return $this->parameter;
    }
}