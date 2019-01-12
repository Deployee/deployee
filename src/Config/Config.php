<?php

namespace Deployee\Config;


class Config implements ConfigInterface
{
    /**
     * @var array|\ArrayAccess
     */
    private $params;

    /**
     * Config constructor.
     * @param array|\ArrayAccess|null $params
     */
    public function __construct($params = null)
    {
        if($params !== null){
            $this->setParams($params);
        }
    }

    /**
     * @param array|\ArrayAccess $params
     */
    public function setParams($params)
    {
        if(!is_array($params)
            && !$params instanceof \ArrayAccess){
            throw new \InvalidArgumentException("Params must be array or implement \\ArrayAcess interface");
        }

        $this->params = $params;
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function get($name, $default = null)
    {
        return $this->params[$name] ?? $default;
    }
}