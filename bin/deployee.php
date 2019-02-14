<?php

use Deployee\Kernel\Kernel;
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
    echo 'Could not find autoloader file' . PHP_EOL;
    exit(255);
}

require $loaderFile;

$input = new ArgvInput();
$env = $input->getParameterOption(['--env', '-e'], getenv('DEPLOYEE_ENV') ?? KernelConstraints::ENV_PROD);


try {
    $kernel = new Kernel($env);
    return $kernel->boot()->run();
}
catch (\Exception $e){
    echo sprintf(
        "An error occured: %s\n\nThe following trace provied some detailed information\n\n%s",
        $e->getMessage(),
        $e->getTraceAsString()
    );

    exit(255);
}