<?php

namespace Deployee\Plugins\OxidEshopTasks;


use Deployee\Dependency\DependencyInjectionProviderInterface;
use Deployee\Deployment\Helper\TaskCreationHelper;
use Deployee\Kernel\Locator;
use Deployee\Plugins\MySqlTasks\Helper\Credentials;
use Deployee\Plugins\OxidEshopTasks\Shop\ShopConfig;
use Deployee\Plugins\RunDeploy\Dispatcher\DispatcherCollection;
use Deployee\Plugins\ShellTasks\Helper\ExecutableFinder;

class DependencyInjectionProvider implements DependencyInjectionProviderInterface
{
    /**
     * @param Locator $locator
     */
    public function injectDependencies(Locator $locator)
    {
        $locator->Dependency()->extendDependency(\Deployee\Plugins\MySqlTasks\Module::CREDENTIALS_DEPENDENCY, function(Credentials $credentials) use($locator){
            /* @var ShopConfig $shopConfig */
            $shopConfig = $locator->Dependency()->getFacade()->getDependency(Module::SHOP_CONFIG_DEPENDENCY);

            $host = explode(':', $shopConfig->get('dbHost'));

            $credentials->setDatabase($shopConfig->get('dbName'));
            $credentials->setUsername($shopConfig->get('dbUser'));
            $credentials->setPassword($shopConfig->get('dbPwd'));
            $credentials->setHost($host[0]);
            $credentials->setPort(isset($host[1]) ? $host[1] : 3306);

            return $credentials;
        });

        $searchPaths = [
            getcwd(),
            $locator->Config()->get('oxid.path'),
            $locator->Config()->get('oxid.path') . '/modules'
        ];

        foreach($searchPaths as $searchPath){
            $expectedPath = $searchPath . '/vendor/bin/oxid';
            if(is_file($expectedPath)){
                $locator->Dependency()->extendDependency(\Deployee\Plugins\ShellTasks\Module::EXECUTABLE_FINDER_DEPENDENCY, function(ExecutableFinder $finder) use ($expectedPath){
                    $finder->addAlias('vendor/bin/oxid', $expectedPath);
                    return $finder;
                });
                break;
            }
        }

        $locator->Dependency()->extendDependency(\Deployee\Deployment\Module::DEFINITION_HELPER_TASK_CREATION_DEPENDENCY, function(TaskCreationHelper $helper){
            $helper->addAlias('oxidClearTmp', 'Deployee\Plugins\OxidEshopTasks\Definitions\ClearShopTempDefinition');
            $helper->addAlias('oxidGenerateViews', 'Deployee\Plugins\OxidEshopTasks\Definitions\GenerateViewsDefinition');
            $helper->addAlias('oxidCreateAdminUser', 'Deployee\Plugins\OxidEshopTasks\Definitions\CreateAdminUserDefinition');
            $helper->addAlias('oxidConfigDatabase', 'Deployee\Plugins\OxidEshopTasks\Definitions\ConfigDatabaseDefinition');
            $helper->addAlias('oxidLangKey', 'Deployee\Plugins\OxidEshopTasks\Definitions\LanguageKeyDefinition');
            $helper->addAlias('oxidModule', 'Deployee\Plugins\OxidEshopTasks\Definitions\ModuleDefinition');
            return $helper;
        });

        $locator->Dependency()->extendDependency(\Deployee\Plugins\RunDeploy\Module::DISPATCHER_COLLECTION_DEPENDENCY, function(DispatcherCollection $collection) use($locator){
            $addDispatcher = [
                'Deployee\Plugins\OxidEshopTasks\Dispatcher\ClearShopTempDispatcher',
                'Deployee\Plugins\OxidEshopTasks\Dispatcher\CreateAdminUserDispatcher',
                'Deployee\Plugins\OxidEshopTasks\Dispatcher\GenerateViewsDispatcher',
                'Deployee\Plugins\OxidEshopTasks\Dispatcher\ConfigDatabaseDispatcher',
                'Deployee\Plugins\OxidEshopTasks\Dispatcher\LanguageKeyDispatcher',
                'Deployee\Plugins\OxidEshopTasks\Dispatcher\ModuleDispatcher',
            ];

            foreach($addDispatcher as $dispatcherClass){
                $collection->addDispatcher(
                    $locator->RunDeploy()->getFactory()->createDispatcher($dispatcherClass)
                );
            }

            return $collection;
        });

        $this->backwardsCompatibility($locator);
    }

    /**
     * @deprecated
     * @param Locator $locator
     */
    private function backwardsCompatibility(Locator $locator)
    {
        $locator->Dependency()->extendDependency(\Deployee\Deployment\Module::DEFINITION_HELPER_TASK_CREATION_DEPENDENCY, function(TaskCreationHelper $helper){
            // v0.1
            $helper->addAlias('oxidShop', 'Deployee\Plugins\OxidEshopTasks\Compatibility\BackwardsCompatibilityDefinition');
            $helper->addAlias('oxidShopLangKey', 'Deployee\Plugins\OxidEshopTasks\Definitions\LanguageKeyDefinition');
            $helper->addAlias('oxidShopConfig', 'Deployee\Plugins\OxidEshopTasks\Definitions\ConfigDatabaseDefinition');

            // v0.0
            $helper->addAlias('shop', 'Deployee\Plugins\OxidEshopTasks\Compatibility\BackwardsCompatibilityDefinition');
            $helper->addAlias('module', 'Deployee\Plugins\OxidEshopTasks\Definitions\ModuleDefinition');
            $helper->addAlias('shopConfig', 'Deployee\Plugins\OxidEshopTasks\Definitions\ConfigDatabaseDefinition');
            return $helper;
        });

        $locator->Dependency()->extendDependency(\Deployee\Plugins\RunDeploy\Module::DISPATCHER_COLLECTION_DEPENDENCY, function(DispatcherCollection $collection) use($locator){
            $collection->addDispatcher(
                $locator->RunDeploy()->getFactory()->createDispatcher('Deployee\Plugins\OxidEshopTasks\Compatibility\BackwardsCompatibilityDispatcher')
            );

            return $collection;
        });
    }

}