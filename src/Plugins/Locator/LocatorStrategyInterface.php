<?php


namespace Deployee\Plugins\Locator;


interface LocatorStrategyInterface
{
    /**
     * @return string[]
     */
    public function locate(): array;
}