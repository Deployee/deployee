<?php


namespace Deployee\Kernel;

use Deployee\Components\Application\Application;
use Deployee\Components\Application\CommandCollection;
use Deployee\Components\Container\Container;
use Deployee\Components\Container\ContainerInterface;
use Deployee\Components\Logger\Logger;
use Deployee\Components\Logger\LoggerInterface;
use Deployee\Components\Plugins\PluginLoader;
use Deployee\Config\ConfigInterface;
use Deployee\Config\ConfigLoader;
use Deployee\Config\ConfigLocator;
use Symfony\Component\Console\Input\ArgvInput;

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
        $this->container = new Container([
            KernelConstraints::ENV => $env,
            LoggerInterface::class => new Logger(KernelConstraints::APPLICATION_NAME),
        ]);
    }

    /**
     * @return Kernel
     * @throws \Deployee\Components\Container\ContainerException
     */
    public function boot(): self
    {
        $this->container->set(ConfigInterface::class, function(){
            return (new ConfigLoader())->load($this->getConfigFilepath());
        });

        $this->container->set(CommandCollection::class, new CommandCollection());
        $this->container->set(ArgvInput::class, function(){
            $args = array_filter($_SERVER['argv'], function($val){
                return strpos($val, '-e=') !== 0
                    && strpos($val, '--env=') !== 0;
            });

            return new ArgvInput($args);
        });
        $this->container->set(Application::class, new Application());

        $pluginLoader = new PluginLoader($this->container);
        $this->container->set(KernelConstraints::PLUGIN_COLLECTION, $pluginLoader->loadPlugins());

        return $this;
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function run()
    {
        /* @var Application $app */
        $app = $this->container->get(Application::class);
        /* @var CommandCollection $commands */
        $commands = $this->container->get(CommandCollection::class);
        /* @var ArgvInput $input */
        $input = $this->container->get(ArgvInput::class);

        $app->addCommands($commands->getCommands());

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
            dirname(__DIR__) . '/../../../../.deployee',
            dirname(__DIR__) . '/../../../..',
        ]);
    }
}