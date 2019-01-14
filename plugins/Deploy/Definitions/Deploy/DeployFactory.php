<?php


namespace Deployee\Plugins\Deploy\Definitions\Deploy;


use Deployee\Components\Container\ContainerInterface;

class DeployFactory
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $class
     * @return DeployDefinitionInterface
     */
    public function createDeploy(string $class): DeployDefinitionInterface
    {
        /* @var DeployDefinitionInterface $object */
        $object = new $class;
        $object->setContainer($this->container);

        return $object;
    }
}