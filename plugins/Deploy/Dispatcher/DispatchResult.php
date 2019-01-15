<?php

namespace Deployee\Plugins\Deploy\Dispatcher;


class DispatchResult implements DispatchResultInterface
{
    /**
     * @var int
     */
    private $exitCode;

    /**
     * @var string
     */
    private $stdOutput;

    /**
     * @var string
     */
    private $errOutput;

    /**
     * DispatchResult constructor.
     * @param int $exitCode
     * @param string $stdOutput
     * @param string $errOutput
     */
    public function __construct(int $exitCode, string $stdOutput, string $errOutput = '')
    {
        $this->exitCode = $exitCode;
        $this->stdOutput = $stdOutput;
        $this->errOutput = $errOutput;
    }

    /**
     * @return int
     */
    public function getExitCode(): int
    {
        return $this->exitCode;
    }

    /**
     * @return string
     */
    public function getOutput(): string
    {
        return $this->stdOutput;
    }

    /**
     * @return string
     */
    public function getErrorOutput(): string
    {
        return $this->errOutput;
    }
}