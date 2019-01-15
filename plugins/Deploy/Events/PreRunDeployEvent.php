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
     * @param InputInterface $input
     * @param ContainerInterface $container
     */
    public function __construct(InputInterface $input)
    {
        $this->input = $input;
    }

    /**
     * @return InputInterface
     */
    public function getInput(): InputInterface
    {
        return $this->input;
    }
}