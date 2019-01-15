<?php

namespace Deployee\Plugins\Deploy\Dispatcher;


class DispatcherCollection
{
    /**
     * @var array
     */
    private $dispatcher;

    /**
     * DispatcherCollection constructor.
     */
    public function __construct()
    {
        $this->dispatcher = [];
    }

    /**
     * @param TaskDefinitionDispatcherInterface $dispatcher
     */
    public function addDispatcher(TaskDefinitionDispatcherInterface $dispatcher)
    {
        $this->dispatcher[] = $dispatcher;
    }

    /**
     * @return TaskDefinitionDispatcherInterface[]
     */
    public function toArray(): array
    {
        return $this->dispatcher;
    }
}