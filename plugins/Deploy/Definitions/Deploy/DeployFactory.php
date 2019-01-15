<?php


namespace Deployee\Plugins\Deploy\Definitions\Deploy;


use Deployee\Components\Container\ContainerInterface;
use Deployee\Components\Dependency\ContainerResolver;

class DeployFactory
{
    /**
     * @var ContainerResolver
     */
    private $resolver;

    /**
     * @param ContainerResolver $resolver
     */
    public function __construct(ContainerResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * @param string $class
     * @return DeployDefinitionInterface
     * @throws \ReflectionException
     */
    public function createDeploy(string $class): DeployDefinitionInterface
    {
        /* @var DeployDefinitionInterface $object */
        $object = $this->resolver->createInstance($class);
        return $object;
    }
}