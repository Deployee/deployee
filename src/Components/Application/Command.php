<?php


namespace Deployee\Components\Application;

use Deployee\Components\Container\ContainerInterface;
use Deployee\Kernel\KernelInterface;

abstract class Command extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function setKernel(ContainerInterface $container)
    {
        $this->container = $container;
    }
}