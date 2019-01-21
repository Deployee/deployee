<?php

use Deployee\Kernel\Kernel;
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

$env = (new ArgvInput())->getParameterOption(['--env', '-e'], getenv('DEPLOYEE_ENV') ?: 'production');
$kernel = new Kernel($env);

$args = array_filter($_SERVER['argv'], function ($arg){
    return strpos($arg, '--env=') !== 0
        && strpos($arg, '-e') !== 0;
});

return $kernel->boot()->run(new ArgvInput($args));