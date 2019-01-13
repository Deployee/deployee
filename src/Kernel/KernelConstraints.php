<?php


namespace Deployee\Kernel;


abstract class KernelConstraints
{
    const ENV = 'env';

    const ENV_PROD = 'prod';

    const ENV_TEST = 'test';

    const ENV_DEV = 'dev';

    const APPLICATION_NAME = 'Deployee';

    const PLUGIN_COLLECTION = 'plugincollection';

    const LOCATOR = "Locator";

    const MODULE_CLASS_LOADER = "ClassLoader";
}