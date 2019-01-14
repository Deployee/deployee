<?php

namespace Deployee\Plugins\Deploy\Events;

use Deployee\Components\Container\ContainerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\EventDispatcher\Event;

class PreRunDeployEvent extends Event
{
    /**
     * @var InputInterface
     */
    private $input;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param InputInterface $input
     * @param ContainerInterface $container
     */
    public function __construct(InputInterface $input, ContainerInterface $container)
    {
        $this->input = $input;
        $this->container = $container;
    }

    /**
     * @return InputInterface
     */
    public function getInput(): InputInterface
    {
        return $this->input;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}