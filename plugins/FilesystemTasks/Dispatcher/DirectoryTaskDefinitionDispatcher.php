<?php


namespace Deployee\Plugins\FilesystemTasks\Dispatcher;


use Deployee\Plugins\Deploy\Definitions\Tasks\TaskDefinitionInterface;
use Deployee\Plugins\Deploy\Dispatcher\DispatchResult;
use Deployee\Plugins\Deploy\Dispatcher\DispatchResultInterface;
use Deployee\Plugins\Deploy\Dispatcher\TaskDefinitionDispatcherInterface;
use Deployee\Plugins\FilesystemTasks\Definitions\DirectoryTaskDefinition;
use Deployee\Plugins\FilesystemTasks\Utils\RmDir;

class DirectoryTaskDefinitionDispatcher implements TaskDefinitionDispatcherInterface
{
    /**
     * @param TaskDefinitionInterface $taskDefinition
     * @return bool
     */
    public function canDispatchTaskDefinition(TaskDefinitionInterface $taskDefinition): bool
    {
        return $taskDefinition instanceof DirectoryTaskDefinition;
    }

    /**
     * @param TaskDefinitionInterface $taskDefinition
     * @return DispatchResultInterface
     */
    public function dispatch(TaskDefinitionInterface $taskDefinition): DispatchResultInterface
    {
        $definition = $taskDefinition->define();
        if($definition->get('create') === true){
            return $this->createDirectory($definition->get('path'), $definition->get('recursive'));
        }

        if($definition->get('remove') === true) {
            return $this->removeDirectory($definition->get('path'), $definition->get('recursive'));
        }

        throw new \LogicException("Invalid definition");
    }

    /**
     * @param string $directory
     * @param bool $recursive
     * @return DispatchResultInterface
     */
    private function removeDirectory($directory, $recursive):DispatchResultInterface
    {
        try{
            $rmDir = new RmDir();
            $rmDir->remove($directory, $recursive);
        }
        catch(\InvalidArgumentException $e){}
        catch(\RuntimeException $e){}

        return isset($e)
            ? new DispatchResult(
                255,
                '',
                sprintf("Could not remove directory %s", $directory) . PHP_EOL . print_r(error_get_last(), true)
            )
            : new DispatchResult(
                0,
                sprintf("Directory removed: %s", $directory)
            );
    }

    /**
     * @param string $directory
     * @param bool $recursive
     * @return DispatchResult
     */
    private function createDirectory($directory, $recursive):DispatchResult
    {
        if(!mkdir($directory, 0777, $recursive) || !is_dir($directory)){
            return new DispatchResult(
                255,
                '',
                sprintf('Could not create directory %s', $directory) . PHP_EOL . print_r(error_get_last(), true)
            );
        }

        return new DispatchResult(0, sprintf('Directory created: %s', $directory));
    }
}