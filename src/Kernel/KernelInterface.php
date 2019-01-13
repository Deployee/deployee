<?php

namespace Deployee\Kernel;

interface KernelInterface
{
    public function boot();

    public function run();
}