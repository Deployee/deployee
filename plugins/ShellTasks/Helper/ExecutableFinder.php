<?php


namespace Deployee\Plugins\ShellTasks\Helper;


use Phizzl\PhpShellCommand\ShellCommand;

class ExecutableFinder
{
    /**
     * @var array
     */
    private $aliases;

    /**
     * @param array $aliases
     */
    public function __construct(array $aliases = [])
    {
        $this->aliases = $aliases;
    }

    /**
     * @param string $name
     * @return string
     */
    public function find(string $name): string
    {
        $return = $name;
        if(isset($this->aliases[$name])){
            $return = $this->aliases[$name];
        }
        elseif(trim($name) !== ""
            && ($path = $this->which($name))){
            $return = $path;
        }

        return $return;
    }

    /**
     * Usage:
     * ExecutableFinderService::addAlias('mysqldump', '/usr/local/mysql5/bin/mysqldump')
     *
     * @param string $alias
     * @param string $resolved
     */
    public function addAlias($alias, $resolved)
    {
        $this->aliases[$alias] = $resolved;
    }

    /**
     * @param string $name
     * @return string
     */
    private function which(string $name): string
    {
        $isOsWin = stripos(PHP_OS, 'WIN') === 0;

        $which = $isOsWin ? 'where' : 'which';

        $cmd = new ShellCommand($which, $name);
        $result = $cmd->run();

        return $result->getExitCode() > 0 ? '' : trim($result->getOutput());
    }
}