<?php

namespace Deployee\Plugins\ShopwareTasks\Dispatcher;

use Deployee\Plugins\Deploy\Definitions\Tasks\TaskDefinitionInterface;
use Deployee\Plugins\Deploy\Dispatcher\AbstractTaskDefinitionDispatcher;
use Deployee\Plugins\Deploy\Dispatcher\DispatchResultInterface;
use Deployee\Plugins\ShellTasks\Definitions\ShellTaskDefinition;
use Deployee\Plugins\ShopwareTasks\Definitions\CreateAdminUserDefinition;
use Deployee\Plugins\ShopwareTasks\Definitions\ShopwareCommandDefinition;

class CreateAdminUserDispatcher extends AbstractTaskDefinitionDispatcher
{
    /**
     * @param TaskDefinitionInterface $taskDefinition
     * @return bool
     */
    public function canDispatchTaskDefinition(TaskDefinitionInterface $taskDefinition): bool
    {
        return $taskDefinition instanceof CreateAdminUserDefinition;
    }


    public function dispatch(TaskDefinitionInterface $taskDefinition): DispatchResultInterface
    {
        $parameter = $taskDefinition->define();
        $swCommand = new ShopwareCommandDefinition(
            'sw:admin:create',
            sprintf(
                "--username=%s --password=%s --email=%s --name=%s --locale=%s -n",
                escapeshellarg($parameter->get('username')),
                escapeshellarg($parameter->get('password')),
                escapeshellarg($parameter->get('email')),
                escapeshellarg($parameter->get('name')),
                escapeshellarg($parameter->get('locale'))
            )
        );

        return $this->delegate($swCommand);
    }

}