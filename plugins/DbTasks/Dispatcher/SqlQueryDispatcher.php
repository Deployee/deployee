<?php


namespace Deployee\Plugins\DbTasks\Dispatcher;

use Deployee\Plugins\DbTasks\Definitions\SqlFileDefinition;
use Deployee\Plugins\DbTasks\Definitions\SqlQueryDefinition;
use Deployee\Plugins\Deploy\Definitions\Tasks\TaskDefinitionInterface;
use Deployee\Plugins\Deploy\Dispatcher\AbstractTaskDefinitionDispatcher;
use Deployee\Plugins\Deploy\Dispatcher\DispatchResultInterface;
use Deployee\Plugins\FilesystemTasks\Definitions\FileTaskDefinition;

class SqlQueryDispatcher extends AbstractTaskDefinitionDispatcher
{
    /**
     * @param TaskDefinitionInterface $taskDefinition
     * @return bool
     */
    public function canDispatchTaskDefinition(TaskDefinitionInterface $taskDefinition): bool
    {
        return $taskDefinition instanceof SqlQueryDefinition;
    }

    /**
     * @param TaskDefinitionInterface $taskDefinition
     * @return DispatchResultInterface
     * @throws \Deployee\Plugins\Deploy\Exception\DispatcherException
     */
    public function dispatch(TaskDefinitionInterface $taskDefinition): DispatchResultInterface
    {
        $definition = $taskDefinition->define();

        $workFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . sprintf('deployee_tmp_%s.sql', uniqid('', false));
        $fileTask = new FileTaskDefinition($workFile);
        $fileTask->contents($definition->get('query'));
        $result = $this->delegate($fileTask);

        if($result->getExitCode() > 0){
            return $result;
        }

        $sqlFileTask = new SqlFileDefinition($workFile);
        if($definition->get('force') === true){
            $sqlFileTask->force();
        }

        $result = $this->delegate($sqlFileTask);

        $fileTask = new FileTaskDefinition($workFile);
        $fileTask->remove();
        $this->delegate($fileTask);

        return $result;
    }
}