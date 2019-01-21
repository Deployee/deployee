<?php


namespace Deployee\Kernel;

use Deployee\Components\Plugins\PluginInterface;
use Deployee\Components\Plugins\PluginLoader;
use Deployee\Kernel\Exceptions\ConfigFileNotFoundException;
use Symfony\Bridge\ProxyManager\LazyProxy\Instantiator\RuntimeInstantiator;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Finder\Finder;

class Kernel
{
    const APP_NAME = 'Deployee';

    const APP_VERSION = '1.1';

    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * @var string
     */
    private $envName;

    /**
     * @param string $envName
     */
    public function __construct(string $envName = 'production')
    {
        $this->envName = $envName;
        $this->container = new ContainerBuilder();
    }

    /**
     * @return Kernel
     * @throws ConfigFileNotFoundException
     * @throws \Exception
     */
    public function boot(): self
    {
        $configFile = $this->getConfigFilepath();
        $this->container->setProxyInstantiator(new RuntimeInstantiator());
        $this->container->setParameter('kernel.env', $this->envName);
        $this->container->setParameter('kernel.configfile', $configFile);
        $this->container->setParameter('kernel.workdir', dirname($configFile));

        $loader = new YamlFileLoader($this->container, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('services.yaml');

        $loader = new YamlFileLoader($this->container, new FileLocator(dirname($configFile)));
        $loader->load(basename($configFile));

        $this->container->setParameter(
            'deploy_definition_path',
            $this->resolveDeployDefinitionPath(
                $this->container->getParameter('deploy_definition_path')
            )
        );

        $pluginLoader = new PluginLoader($this->container);
        $this->container->set('kernel.services.plugins', $pluginLoader->loadPlugins());

        return $this;
    }

    /**
     * @param InputInterface $input
     * @return int
     * @throws \Exception
     */
    public function run(InputInterface $input): int
    {
        $this->container->set(ArgvInput::class, $input);

        /* @var PluginInterface $plugin */
        foreach($this->container->get('kernel.services.plugins') as $plugin){
            $this->runPlugin($plugin);
        }

        $commandClasses = array_keys($this->container->findTaggedServiceIds('console.command'));
        $commands = [];
        foreach($commandClasses as $commandClasse){
            $commands[] = $this->container->get($commandClasse);
        }

        /* @var Application $app */
        $app = new Application(self::APP_NAME, self::APP_VERSION);
        $app->addCommands($commands);

        return $app->run($input);
    }

    /**
     * @param string $path
     * @return string
     */
    private function resolveDeployDefinitionPath(string $path): string
    {
        return strpos($path, '/') !== 0 && strpos($path, ':') !== 1
            ? $this->container->getParameter('kernel.workdir') . DIRECTORY_SEPARATOR . $path
            : $path;
    }

    /**
     * @param PluginInterface $plugin
     * @throws \Exception
     */
    private function runPlugin(PluginInterface $plugin)
    {
        try {
            $method = new \ReflectionMethod(get_class($plugin), 'run');
        }
        catch(\ReflectionException $e){
            return;
        }

        $args = [];
        foreach ($method->getParameters() as $parameter) {
            if($parameter->getType() === null){
                throw new \InvalidArgumentException(sprintf(
                    'Parameter %s of %s must have a service type hint',
                    $parameter->getName(),
                    $method->getNamespaceName()
                ));
            }

            $args[] = $this->container->get($parameter->getType()->getName());
        }

        $method->invoke($plugin, ...$args);
    }

    /**
     * @return string
     * @throws ConfigFileNotFoundException
     */
    private function getConfigFilepath(): string
    {
        $searchablePaths = [
            dirname(__DIR__) . '/../../../../.deployee',
            dirname(__DIR__) . '/../../../..',
            dirname(__DIR__) . '/../.deployee'
        ];

        foreach($searchablePaths as $searchablePath){
            if(($path = realpath($searchablePath)) === '' || (!is_dir($path) && !is_link($path))){
                continue;
            }

            $name = sprintf(
                '/^(.)?deployee(%s\.dist)?.yaml$/',
                $this->envName !== '' ? sprintf('\.%s|', $this->envName) : ''
            );

            $finder = new Finder();
            $finder
                ->name($name)
                ->followLinks()
                ->files()
                ->ignoreUnreadableDirs()
                ->depth(0)
                ->ignoreDotFiles(false)
                ->in($path)
            ;

            foreach($finder as $fileInfo){
                return $fileInfo->getRealPath();
            }
        }

        throw new ConfigFileNotFoundException('Deployee config file could not be found');
    }
}