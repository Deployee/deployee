<?php

use Deployee\Components\Container\Container;
use Deployee\Kernel\Kernel;
use Deployee\Kernel\Locator;
use Deployee\ClassLoader\Module;
use Deployee\Kernel\KernelConstraints;
use Symfony\Component\Console\Input\ArgvInput;

set_time_limit(0);

$findLoader = [
    dirname(__DIR__) . '/vendor/autoload.php',
    dirname(__DIR__) . '/../../autoload.php',
];

$loaderFile = '';
foreach($findLoader as $expectedFilepath){
    if(is_file($expectedFilepath)){
        $loaderFile = $expectedFilepath;
        break;
    }
}

if($loaderFile === ''){
    throw new \Exception("Could not find autoloader file");
}

require $loaderFile;

$input = new ArgvInput();
$env = $input->getParameterOption(['--env', '-e'], getenv('DEPLOYEE_ENV') ?? KernelConstraints::ENV_PROD);
$kernel = new Kernel($env);

return $kernel->boot()->run();