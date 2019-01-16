<?php


namespace Deployee\Plugins\ShellTasks\Dispatcher;

use Deployee\Plugins\Deploy\Definitions\Tasks\TaskDefinitionInterface;
use Deployee\Plugins\Deploy\Dispatcher\DispatchResult;
use Deployee\Plugins\Deploy\Dispatcher\DispatchResultInterface;
use Deployee\Plugins\Deploy\Dispatcher\TaskDefinitionDispatcherInterface;
use Deployee\Plugins\ShellTasks\Definitions\ShellTaskDefinition;
use Deployee\Plugins\ShellTasks\Helper\ExecutableFinder;
use Phizzl\PhpShellCommand\ShellCommand;

class ShellTaskDefinitionDispatcher implements TaskDefinitionDispatcherInterface
{
    /**
     * @var ExecutableFinder
     */
    private $executableFinder;

    /**
     * @param ExecutableFinder $executableFinder
     */
    public function __construct(ExecutableFinder $executableFinder)
    {
        $this->executableFinder = $executableFinder;
    }

    /**
     * @param TaskDefinitionInterface $taskDefinition
     * @return bool
     */
    public function canDispatchTaskDefinition(TaskDefinitionInterface $taskDefinition): bool
    {
        return $taskDefinition instanceof ShellTaskDefinition;
    }

    /**
     * @param TaskDefinitionInterface $taskDefinition
     * @return DispatchResult
     */
    public function dispatch(TaskDefinitionInterface $taskDefinition): DispatchResultInterface
    {
        $definition = $taskDefinition->define();
        $executable = $this->executableFinder->find($definition->get('executable'));
        $arguments = (string)$definition->get('arguments');

        $cmd = new ShellCommand("$executable $arguments");
        $return = $cmd->run();

        return $return->getExitCode() > 0
            ? new DispatchResult(
                $return->getExitCode(),
                $return->getOutput(),
                sprintf('Error executing: %s (%s)' . PHP_EOL . '%s', $cmd->getCommand(), $return->getExecTime(), $return->getError())
            )
            : new DispatchResult(
                $return->getExitCode(),
                sprintf('Executed command: %s (%s)' . PHP_EOL . '%s', $cmd->getCommand(), $return->getExitCode(), $return->getOutput())
            );
    }
}