<?php

namespace Deployee\Components\Container;

class Container implements ContainerInterface
{
    /**
     * @var \Pimple\Container
     */
    private $container;

    /**
     * @param array $values
     */
    public function __construct(array $values = array())
    {
        $this->container = new \Pimple\Container($values);
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function get(string $id)
    {
        return $this->container[$id];
    }

    /**
     * @param string $id
     * @param mixed $value
     */
    public function set(string $id, $value)
    {
        if(is_callable($value)){
            $me = $this;
            $result = function() use($value, $me){
                return $value($me);
            };

            $value = $result;
        }

        $this->container[$id] = $value;
    }

    /**
     * @param string $id
     * @param callable $callable
     */
    public function extend(string $id, callable $callable)
    {
        $me = $this;
        $this->container->extend($id, function($value) use($callable, $me){
            return $callable($value, $me);
        });
    }
}