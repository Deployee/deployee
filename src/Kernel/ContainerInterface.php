<?php

namespace Deployee\Kernel;


interface ContainerInterface
{
    /**
     * @param string $id
     * @return mixed
     */
    public function get($id);

    /**
     * @param string $id
     * @param mixed $value
     * @return mixed
     */
    public function set($id, $value);

    /**
     * @param string $id
     * @param callable $callable
     */
    public function extend($id, callable $callable);
}