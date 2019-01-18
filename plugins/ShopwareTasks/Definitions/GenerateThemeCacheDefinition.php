<?php

namespace Deployee\Plugins\ShopwareTasks\Definitions;


use Deployee\Plugins\Deploy\Definitions\Parameter\ParameterCollection;
use Deployee\Plugins\Deploy\Definitions\Parameter\ParameterCollectionInterface;
use Deployee\Plugins\Deploy\Definitions\Tasks\TaskDefinitionInterface;

class GenerateThemeCacheDefinition implements TaskDefinitionInterface
{
    /**
     * @var int
     */
    private $shopId;

    /**
     * @param array $shopId
     */
    public function __construct(array $shopId = [])
    {
        $this->shopId = $shopId;
    }

    /**
     * @return ParameterCollectionInterface
     */
    public function define(): ParameterCollectionInterface
    {
        return new ParameterCollection(get_object_vars($this));
    }
}