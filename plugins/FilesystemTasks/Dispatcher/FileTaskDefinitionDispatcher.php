<?php


namespace Deployee\Plugins\FilesystemTasks\Dispatcher;

use Deployee\Plugins\Deploy\Definitions\Tasks\TaskDefinitionInterface;
use Deployee\Plugins\Deploy\Dispatcher\DispatchResult;
use Deployee\Plugins\Deploy\Dispatcher\DispatchResultInterface;
use Deployee\Plugins\Deploy\Dispatcher\TaskDefinitionDispatcherInterface;
use Deployee\Plugins\FilesystemTasks\Definitions\FileTaskDefinition;
use Deployee\Plugins\FilesystemTasks\Utils\Rm;

class FileTaskDefinitionDispatcher implements TaskDefinitionDispatcherInterface
{
    /**
     * @param TaskDefinitionInterface $taskDefinition
     * @return bool
     */
    public function canDispatchTaskDefinition(TaskDefinitionInterface $taskDefinition): bool
    {
        return $taskDefinition instanceof FileTaskDefinition;
    }

    /**
     * @param TaskDefinitionInterface $taskDefinition
     * @return DispatchResult
     */
    public function dispatch(TaskDefinitionInterface $taskDefinition): DispatchResultInterface
    {
        $definition = $taskDefinition->define();
        if($definition->get('remove') === true){
            return $this->removeFile($definition->get('path'));
        }

        if($definition->get('symlink') !== null){
            return $this->createSymlink($definition->get('symlink'), $definition->get('path'));
        }

        if($definition->get('copy') !== null) {
            return $this->copyFile($definition->get('copy'), $definition->get('path'));
        }

        return $this->setFileContents($definition->get('path'), $definition->get('contents'));
    }

    private function setFileContents(string $file, string $contents): DispatchResult
    {
        if(($bytes = file_put_contents($file, $contents)) === false){
            return new DispatchResult(
                255,
                '',
                sprintf('Could not write to file %s', $file) . PHP_EOL . print_r(error_get_last(), true)
            );
        }

        return new DispatchResult(0, sprintf('Wrote %s bytes to %s', $bytes, $file));
    }

    /**
     * @param string $source
     * @param string $target
     * @return DispatchResult
     */
    private function copyFile(string $source, string $target): DispatchResult
    {
        if (copy($source, $target) === false) {
            return new DispatchResult(
                255,
                '',
                sprintf('Could not copy file from %s to %s', $source, $target) . PHP_EOL .
                implode(PHP_EOL, error_get_last())
            );
        }

        return new DispatchResult(0, sprintf("Copied: %s -> %s", $source, $target));
    }

    /**
     * @param string $linkSource
     * @param string $linkTarget
     * @return DispatchResult
     */
    private function createSymlink(string $linkSource, string $linkTarget)
    {
        if(symlink($linkSource, $linkTarget) === false){
            return new DispatchResult(
                255,
                '',
                sprintf("Could not create symlink from %s to %s", $linkSource, $linkTarget) . PHP_EOL .
                implode(PHP_EOL, error_get_last())
            );
        }

        return new DispatchResult(0, sprintf("Linked: %s -> %s", $linkSource, $linkTarget));
    }

    /**
     * @param string $path
     * @return DispatchResult
     */
    private function removeFile(string $path): DispatchResult
    {
        $rm = new Rm();
        try {
            $rm->remove($path);
            $return = new DispatchResult(0, sprintf("Removed: %s", $path));
        }
        catch(\RuntimeException $e){
            $return = new DispatchResult(255, '', $e->getMessage() . PHP_EOL . $e->getTraceAsString());
        }

        return $return;
    }
}