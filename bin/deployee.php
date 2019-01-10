<?php

use Deployee\Kernel\Locator;
use Composer\Autoload\ClassLoader;
use Deployee\ClassLoader\Module;
use Deployee\Kernel\DependencyProviderContainer;
use Deployee\Kernel\KernelConstants;

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

/* @var ClassLoader $loader */
$loader = require $loaderFile;
$namespaces = array_reverse(array_keys($loader->getPrefixesPsr4()));

$dependencyProviderContainer = new DependencyProviderContainer();
$locator = new Locator($dependencyProviderContainer, $namespaces);

$dependencyProviderContainer[Module::CLASS_LOADER_CONTAINER_ID] = $loader;
$dependencyProviderContainer[KernelConstants::LOCATOR] = $locator;

$locator->Application()->getFacade()->runApplication();