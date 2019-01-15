<?php


namespace Deployee\Plugins\Deploy;


use Deployee\Components\Application\CommandCollection;
use Deployee\Components\Config\ConfigInterface;
use Deployee\Components\Container\ContainerInterface;
use Deployee\Components\Dependency\ContainerResolver;
use Deployee\Components\Plugins\PluginInterface;
use Deployee\Kernel\KernelConstraints;
use Deployee\Plugins\Deploy\Commands\DeployRunCommand;
use Deployee\Plugins\Deploy\Definitions\Deploy\DeployFactory;
use Deployee\Plugins\Deploy\Definitions\Tasks\TaskFactory;
use Deployee\Plugins\Deploy\Dispatcher\DispatcherCollection;
use Deployee\Plugins\Deploy\Dispatcher\DispatcherFinder;
use Deployee\Plugins\Deploy\Finder\DeployDefinitionFileFinder;
use Deployee\Plugins\Deploy\Helper\TaskCreationHelper;

class DeployPlugin implements PluginInterface
{
    public function boot(ContainerInterface $container)
    {
        $container->set(DeployDefinitionFileFinder::class, function(ContainerInterface $container){
            /* @var ContainerResolver $resolver */
            $resolver = $container->get(ContainerResolver::class);
            /* @var ConfigInterface $config */
            $config = $container->get(ConfigInterface::class);
            $path = $config->get('deploy_definition_path', 'definitions');

            $path = strpos($path, '/') !== 0 && strpos($path, ':') !== 1
                ? $container->get(KernelConstraints::WORKDIR) . DIRECTORY_SEPARATOR . $path
                : $path;


            return $resolver->createInstance(DeployDefinitionFileFinder::class, [$path]);
        });

        $container->set(DeployFactory::class, function(ContainerInterface $container){
            /* @var ContainerResolver $resolver */
            $resolver = $container->get(ContainerResolver::class);
            return $resolver->createInstance(DeployFactory::class);
        });

        $container->set(TaskFactory::class, function(ContainerInterface $container){
            /* @var ContainerResolver $resolver */
            $resolver = $container->get(ContainerResolver::class);
            return $resolver->createInstance(TaskFactory::class);
        });

        $container->set(TaskCreationHelper::class, function(ContainerInterface $container){
            /* @var ContainerResolver $resolver */
            $resolver = $container->get(ContainerResolver::class);
            return $resolver->createInstance(TaskCreationHelper::class);
        });

        $container->set(DispatcherCollection::class, function(){
            return new DispatcherCollection();
        });

        $container->set(DispatcherFinder::class, function(ContainerInterface $container){
            /* @var ContainerResolver $resolver */
            $resolver = $container->get(ContainerResolver::class);
            return $resolver->createInstance(DispatcherFinder::class);
        });
    }

    public function configure(ContainerInterface $container)
    {
        /* @var CommandCollection $cmdCollection */
        $cmdCollection = $container->get(CommandCollection::class);
        $cmdCollection->addCommand(new DeployRunCommand());
    }
}