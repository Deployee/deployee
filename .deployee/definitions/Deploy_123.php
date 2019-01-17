<?php


use Deployee\Plugins\Deploy\Definitions\Deploy\AbstractDeployDefinition;

class Deploy_123 extends AbstractDeployDefinition
{
    public function define()
    {

        $this->sqlFile(__DIR__ . '/test.sql');
    }

}