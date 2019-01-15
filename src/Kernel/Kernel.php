<?php


namespace Deployee\Kernel;

use Deployee\Components\Application\Application;
use Deployee\Components\Application\CommandCollection;
use Deployee\Components\Config\ConfigInterface;
use Deployee\Components\Config\ConfigLoader;
use Deployee\Components\Config\ConfigLocator;
use Deployee\Components\Container\Container;
use Deployee\Components\Container\ContainerInterface;
use Deployee\Components\Dependency\ContainerResolver;
use Deployee\Components\Plugins\PluginInterface;
use Deployee\Components\Plugins\PluginLoader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

class Kernel implements KernelInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param string $env
     * @throws \Deployee\Components\Container\ContainerException
     */
    public function __construct(string $env = KernelConstraints::ENV_PROD)
    {
        $this->container = new Container([KernelConstraints::ENV => $env]);
    }

    /**
     * @return Kernel
     * @throws \Deployee\Components\Container\ContainerException
     */
    public function boot(): self
    {
        $this->container->set(InputInterface::class, function(){
            $args = array_filter($_SERVER['argv'], function($val){
                return strpos($val, '-e=') !== 0
                    && strpos($val, '--env=') !== 0;
            });

            return new ArgvInput($args);
        });

        $configFilepath = $this->getConfigFilepath();
        $this->container->set(KernelConstraints::WORKDIR, dirname($configFilepath));
        $this->container->set(ConfigInterface::class, function() use($configFilepath){
            return (new ConfigLoader())->load($configFilepath);
        });

        $this->container->set(ContainerResolver::class, function(ContainerInterface $container){
            return new ContainerResolver($container);
        });

        $this->container->set(CommandCollection::class, new CommandCollection());
        $this->container->set(Application::class, new Application());
        $this->container->set(EventDispatcher::class, new EventDispatcher());

        $pluginLoader = new PluginLoader($this->container);
        $this->container->set(KernelConstraints::PLUGIN_COLLECTION, $pluginLoader->loadPlugins());


        return $this;
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function run(): int
    {
        $container = $this->container;
        /* @var Application $app */
        $app = $this->container->get(Application::class);
        $commands = $this->container->get(CommandCollection::class)->getCommands();

        array_walk($commands, function(Command $cmd) use($container){
            /* @var ContainerResolver $resolver */
            $resolver = $container->get(ContainerResolver::class);
            $resolver->autowireObject($cmd);
        });
        $app->addCommands($commands);

        /* @var InputInterface $input */
        $input = $this->container->get(InputInterface::class);

        return $app->run($input);
    }

    /**
     * @return string
     */
    private function getConfigFilepath(): string
    {
        $locator = new ConfigLocator();
        return $locator->locate([
            dirname(__DIR__) . '/..',
            dirname(__DIR__) . '/../.deployee',
            dirname(__DIR__) . '/../../../../.deployee',
            dirname(__DIR__) . '/../../../..',
        ]);
    }
}