<?php

namespace Deployee\Plugins\FilesystemTasks\Definitions;

use Deployee\Plugins\Deploy\Definitions\Parameter\ParameterCollection;
use Deployee\Plugins\Deploy\Definitions\Parameter\ParameterCollectionInterface;
use Deployee\Plugins\Deploy\Definitions\Tasks\TaskDefinitionInterface;

class FileTaskDefinition implements TaskDefinitionInterface
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
     * @param string $contents
     * @return $this
     */
    public function contents(string $contents): self
    {
        $this->parameter->set('contents', $contents);
        $this->parameter->set('remove', false);
        $this->parameter->set('copy', null);
        $this->parameter->set('symlink', null);
        return $this;
    }

    /**
     * @return $this
     */
    public function remove(): self
    {
        $this->parameter->set('remove', true);
        $this->parameter->set('contents', null);
        $this->parameter->set('copy', null);
        $this->parameter->set('symlink', null);
        return $this;
    }

    /**
     * @param string $source
     * @return $this
     */
    public function copy(string $source): self
    {
        $this->parameter->set('copy', $source);
        $this->parameter->set('contents', null);
        $this->parameter->set('remove', null);
        $this->parameter->set('symlink', null);
        return $this;
    }

    /**
     * @param string $symlinkSource
     * @return FileTaskDefinition
     */
    public function symlink(string $symlinkSource): self
    {
        $this->parameter->set('symlink', $symlinkSource);
        $this->parameter->set('copy', null);
        $this->parameter->set('contents', null);
        $this->parameter->set('remove', null);
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