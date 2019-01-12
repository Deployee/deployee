<?php

namespace Deployee\Components\Container;


interface ContainerInterface
{
    /**
     * @param string $id
     * @return mixed
     */
    public function get(string $id);

    /**
     * @param string $id
     * @param mixed $value
     * @return mixed
     */
    public function set(string $id, $value);

    /**
     * @param string $id
     * @param callable $callable
     */
    public function extend(string $id, callable $callable);
}