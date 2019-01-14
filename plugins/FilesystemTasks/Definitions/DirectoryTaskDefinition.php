<?php

namespace Deployee\Plugins\FilesystemTasks\Definitions;


use Deployee\Plugins\Deploy\Definitions\Parameter\ParameterCollection;
use Deployee\Plugins\Deploy\Definitions\Parameter\ParameterCollectionInterface;
use Deployee\Plugins\Deploy\Definitions\Tasks\AbstractTaskDefinition;

class DirectoryTaskDefinition extends AbstractTaskDefinition
{
    /**
     * @var ParameterCollectionInterface
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
     * @return $this
     */
    public function create(): self
    {
        $this->parameter->set('create', true);
        $this->parameter->set('remove', false);
        return $this;
    }

    /**
     * @return $this
     */
    public function remove(): self
    {
        $this->parameter->set('remove', true);
        $this->parameter->set('create', false);
        return $this;
    }

    /**
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