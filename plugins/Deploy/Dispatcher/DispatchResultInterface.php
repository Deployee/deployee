<?php

namespace Deployee\Plugins\Deploy\Dispatcher;

interface DispatchResultInterface
{
    /**
     * @return int
     */
    public function getExitCode(): int;

    /**
     * @return string
     */
    public function getOutput(): string;

    /**
     * @return string
     */
    public function getErrorOutput(): string;
}