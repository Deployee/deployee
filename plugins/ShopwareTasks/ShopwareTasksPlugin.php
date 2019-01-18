<?php


namespace Deployee\Plugins\ShopwareTasks;


use Deployee\Components\Config\ConfigInterface;
use Deployee\Components\Container\ContainerInterface;
use Deployee\Components\Dependency\ContainerResolver;
use Deployee\Components\Persistence\LazyPDO;
use Deployee\Components\Plugins\PluginInterface;
use Deployee\Plugins\Deploy\Dispatcher\DispatcherCollection;
use Deployee\Plugins\Deploy\Helper\TaskCreationHelper;
use Deployee\Plugins\ShellTasks\Helper\ExecutableFinder;
use Deployee\Plugins\ShopwareTasks\Definitions\CreateAdminUserDefinition;
use Deployee\Plugins\ShopwareTasks\Definitions\ShopwareCommandDefinition;
use Deployee\Plugins\ShopwareTasks\Dispatcher\CreateAdminUserDispatcher;
use Deployee\Plugins\ShopwareTasks\Dispatcher\ShopwareCommandDispatcher;
use Deployee\Plugins\ShopwareTasks\Shop\ShopConfig;

class ShopwareTasksPlugin implements PluginInterface
{
    public function boot(ContainerInterface $container)
    {
        $container->set(ShopConfig::class, function(ContainerInterface $container){
            /* @var ConfigInterface $config  */
            $config = $container->get(ConfigInterface::class);
            $path = $config->get('shopware.path', '') . DIRECTORY_SEPARATOR . 'config.php';

            return new ShopConfig($path);
        });

        $container->extend(LazyPDO::class, function(LazyPDO $lazyPDO) use($container){
            /* @var ConfigInterface $config */
            $config = $container->get(ConfigInterface::class);

            /* @var ShopConfig $shopConfig */
            $shopConfig = $container->get(ShopConfig::class);
            $db = $shopConfig->get('db');

            $lazyPDO->changeConnection(
                sprintf(
                    '%s:host=%s;port=%d;dbname=%s',
                    'mysql',
                    $config->get('db.host') ?? $db['host'],
                    $config->get('db.port') ?? $db['port'],
                    $config->get('db.database') ?? $db['dbname']
                ),
                $config->get('db.user') ?? $db['username'],
                $config->get('db.password') ?? $db['password'],
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
                ]
            );

            return $lazyPDO;
        });
    }

    /**
     * @param ContainerInterface $container
     * @throws \ReflectionException
     */
    public function configure(ContainerInterface $container)
    {
        /* @var ConfigInterface $config  */
        $config = $container->get(ConfigInterface::class);

        /* @var ExecutableFinder $execFinder */
        $execFinder = $container->get(ExecutableFinder::class);
        $execFinder->addAlias('swconsole', $config->get('shopware.path') . '/bin/console');

        /* @var TaskCreationHelper $helper */
        $helper = $container->get(TaskCreationHelper::class);
        $helper->addAlias('swCommand', ShopwareCommandDefinition::class);
        $helper->addAlias('swCreateAdmin', CreateAdminUserDefinition::class);

        /* @var DispatcherCollection $dispatcherCollection */
        $dispatcherCollection = $container->get(DispatcherCollection::class);
        /* @var ContainerResolver $resolver */
        $resolver = $container->get(ContainerResolver::class);

        $dispatcherArray = [
            $resolver->createInstance(ShopwareCommandDispatcher::class),
            $resolver->createInstance(CreateAdminUserDispatcher::class)
        ];

        $dispatcherCollection->addDispatcherArray($dispatcherArray);
    }

}