<?php

namespace Deployee\Kernel;

class Container implements ContainerInterface
{
    /**
     * @var \Pimple\Container
     */
    private $container;

    public function __construct(array $values = array())
    {
        $this->container = new \Pimple\Container($values);
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function get($id)
    {
        return $this->container[$id];
    }

    /**
     * @param string $id
     * @param mixed $value
     */
    public function set($id, $value)
    {
        $this->container[$id] = $value;
    }

    /**
     * @param string $id
     * @param callable $callable
     */
    public function extend($id, callable $callable)
    {
        $me = $this;
        $this->container->extend($id, function($value) use($callable, $me){
            return call_user_func_array($callable, [$value, $me]);
        });
    }
}