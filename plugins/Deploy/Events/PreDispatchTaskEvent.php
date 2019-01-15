<?php

namespace Deployee\Plugins\Deploy\Events;


use Deployee\Plugins\Deploy\Definitions\Tasks\TaskDefinitionInterface;
use Symfony\Component\EventDispatcher\Event;

class PreDispatchTaskEvent extends Event
{
    /**
     * @var TaskDefinitionInterface
     */
    private $task;

    /**
     * @var bool
     */
    private $preventDispatch;

    /**
     * PreDispatchTaskEvent constructor.
     * @param TaskDefinitionInterface $task
     */
    public function __construct(TaskDefinitionInterface $task)
    {
        $this->task = $task;
        $this->preventDispatch = false;
    }

    /**
     * @return TaskDefinitionInterface
     */
    public function getTask(): TaskDefinitionInterface
    {
        return $this->task;
    }

    /**
     * @return bool
     */
    public function isPreventDispatch(): bool
    {
        return $this->preventDispatch;
    }

    /**
     * @param bool $preventDispatch
     */
    public function setPreventDispatch(bool $preventDispatch)
    {
        $this->preventDispatch = $preventDispatch;
    }
}