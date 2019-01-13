<?php

namespace Deployee\Components\Config;

interface ConfigInterface
{
    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function get($name, $default = null);
}