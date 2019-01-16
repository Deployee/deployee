<?php

namespace Deployee\Plugins\Deploy\Events;

use Deployee\Plugins\Deploy\Definitions\Tasks\TaskDefinitionInterface;
use Deployee\Plugins\Deploy\Dispatcher\DispatchResultInterface;
use Symfony\Component\EventDispatcher\Event;

class PostDispatchTaskEvent extends Event
{
    /**
     * @var TaskDefinitionInterface
     */
    private $task;

    /**
     * @var DispatchResultInterface
     */
    private $result;

    /**
     * PostDispatchTaskEvent constructor.
     * @param TaskDefinitionInterface $task
     * @param DispatchResultInterface $result
     */
    public function __construct(TaskDefinitionInterface $task, DispatchResultInterface $result)
    {
        $this->task = $task;
        $this->result = $result;
    }

    /**
     * @return TaskDefinitionInterface
     */
    public function getTask(): TaskDefinitionInterface
    {
        return $this->task;
    }

    /**
     * @return DispatchResultInterface
     */
    public function getResult(): DispatchResultInterface
    {
        return $this->result;
    }
}