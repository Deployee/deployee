<?php

namespace Deployee\Plugins\Deploy\Events;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\Event;

class FindExecutableDefinitionFilesEvent extends Event
{
    /**
     * @var \ArrayObject
     */
    private $definitionFileCollection;

    /**
     * @var InputInterface
     */
    private $input;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @param \ArrayObject $definitionFileCollection
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function __construct(\ArrayObject $definitionFileCollection, InputInterface $input, OutputInterface $output)
    {
        $this->definitionFileCollection = $definitionFileCollection;
        $this->input = $input;
        $this->output = $output;
    }

    /**
     * @return InputInterface
     */
    public function getInput(): InputInterface
    {
        return $this->input;
    }

    /**
     * @return \ArrayObject
     */
    public function getDefinitionFileCollection(): \ArrayObject
    {
        return $this->definitionFileCollection;
    }

    /**
     * @param \ArrayObject $definitionFileCollection
     */
    public function replaceDefinitionFileCollection(\ArrayObject $definitionFileCollection)
    {
        $this->definitionFileCollection = $definitionFileCollection;
    }

    /**
     * @return OutputInterface
     */
    public function getOutput(): OutputInterface
    {
        return $this->output;
    }
}