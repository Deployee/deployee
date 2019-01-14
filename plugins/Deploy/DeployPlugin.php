<?php


namespace Deployee\Plugins\Deploy;


use Deployee\Components\Application\CommandCollection;
use Deployee\Components\Config\ConfigInterface;
use Deployee\Components\Container\ContainerInterface;
use Deployee\Components\Plugins\PluginInterface;
use Deployee\Kernel\KernelConstraints;
use Deployee\Plugins\Deploy\Commands\DeployRunCommand;
use Deployee\Plugins\Deploy\Definitions\Deploy\DeployFactory;
use Deployee\Plugins\Deploy\Definitions\Tasks\TaskFactory;
use Deployee\Plugins\Deploy\Finder\DeployDefinitionFileFinder;
use Deployee\Plugins\Deploy\Helper\TaskCreationHelper;

class DeployPlugin implements PluginInterface
{
    public function boot(ContainerInterface $container)
    {
        $container->set(DeployDefinitionFileFinder::class, function(ContainerInterface $container){
            /* @var ConfigInterface $config */
            $config = $container->get(ConfigInterface::class);
            $path = $config->get('deploy_definition_path', 'definitions');

            $path = strpos($path, '/') !== 0 && strpos($path, ':') !== 1
                ? $container->get(KernelConstraints::WORKDIR) . DIRECTORY_SEPARATOR . $path
                : $path;


            return new DeployDefinitionFileFinder($path);
        });

        $container->set(DeployFactory::class, function(ContainerInterface $container){
            return new DeployFactory($container);
        });

        $container->set(TaskFactory::class, function(ContainerInterface $container){
            return new TaskFactory($container);
        });

        $container->set(TaskCreationHelper::class, function(ContainerInterface $container){
            return new TaskCreationHelper($container->get(TaskFactory::class));
        });

        /* @var CommandCollection $collection */
        $collection = $container->get(CommandCollection::class);
        $collection->addCommand(
            new DeployRunCommand()
        );
    }

    public function run(ContainerInterface $container)
    {

    }
}