<?php

namespace Deployee\Components\Application;


class CommandCollection
{
    /**
     * @var array
     */
    private $commands;

    /**
     * CommandCollection constructor.
     */
    public function __construct()
    {
        $this->commands = [];
    }

    /**
     * @param \Symfony\Component\Console\Command\Command $command
     */
    public function addCommand(\Symfony\Component\Console\Command\Command $command)
    {
        $this->commands[] = $command;
    }

    /**
     * @return array
     */
    public function getCommands(): array
    {
        return $this->commands;
    }
}