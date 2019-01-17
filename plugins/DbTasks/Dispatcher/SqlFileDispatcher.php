<?php


namespace Deployee\Plugins\DbTasks\Dispatcher;

use Deployee\Components\Config\ConfigInterface;
use Deployee\Components\Persistence\LazyPDO;
use Deployee\Plugins\DbTasks\Definitions\SqlFileDefinition;
use Deployee\Plugins\DbTasks\Helper\Credentials;
use Deployee\Plugins\Deploy\Definitions\Tasks\TaskDefinitionInterface;
use Deployee\Plugins\Deploy\Dispatcher\AbstractTaskDefinitionDispatcher;
use Deployee\Plugins\Deploy\Dispatcher\DispatchResultInterface;
use Deployee\Plugins\ShellTasks\Definitions\ShellTaskDefinition;

class SqlFileDispatcher extends AbstractTaskDefinitionDispatcher
{
    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * @param TaskDefinitionInterface $taskDefinition
     * @return bool
     */
    public function canDispatchTaskDefinition(TaskDefinitionInterface $taskDefinition): bool
    {
        return $taskDefinition instanceof SqlFileDefinition;
    }

    /**
     * @param TaskDefinitionInterface $taskDefinition
     * @return DispatchResultInterface
     * @throws \Deployee\Plugins\Deploy\Exception\DispatcherException
     */
    public function dispatch(TaskDefinitionInterface $taskDefinition): DispatchResultInterface
    {
        $definition = $taskDefinition->define();
        $shellTask = new ShellTaskDefinition($this->config->get('db.type', 'mysql'));
        $shellTask->arguments(sprintf(
            '--host=%s --port=%s --user=%s --password=%s %s %s < %s',
            escapeshellarg($this->config->get('db.host', 'localhost')),
            escapeshellarg($this->config->get('db.port', 3306)),
            escapeshellarg($this->config->get('db.user', 'root')),
            escapeshellarg($this->config->get('db.password', '')),
            $definition->get('force') === true ? '---force' : '',
            escapeshellarg($this->config->get('db.database', '')),
            $definition->get('source')
        ));

        return $this->delegate($shellTask);
    }
}