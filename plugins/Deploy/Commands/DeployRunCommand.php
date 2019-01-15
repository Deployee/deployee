<?php

namespace Deployee\Plugins\Deploy\Commands;

use Composer\Autoload\ClassLoader;
use Deployee\Components\Container\ContainerInterface;
use Deployee\Plugins\Deploy\Definitions\Deploy\DeployFactory;
use Deployee\Plugins\Deploy\Definitions\Deploy\DeployDefinitionInterface;
use Deployee\Plugins\Deploy\Definitions\Tasks\TaskDefinitionInterface;
use Deployee\Plugins\Deploy\Dispatcher\DispatcherFinder;
use Deployee\Plugins\Deploy\Dispatcher\DispatchResult;
use Deployee\Plugins\Deploy\Dispatcher\DispatchResultInterface;
use Deployee\Plugins\Deploy\Events\FindExecutableDefinitionFilesEvent;
use Deployee\Plugins\Deploy\Events\PreDispatchTaskEvent;
use Deployee\Plugins\Deploy\Events\PreRunDeployEvent;
use Deployee\Plugins\Deploy\Exception\FailedException;
use Deployee\Plugins\Deploy\Finder\DeployDefinitionFileFinder;
use Deployee\Plugins\Deploy\Events\PreDispatchDeploymentEvent;
use Deployee\Plugins\Deploy\Events\PostDispatchDeploymentEvent;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

class DeployRunCommand extends Command
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var DispatcherFinder
     */
    private $dispatcherFinder;

    /**
     * @var DeployFactory
     */
    private $deployFactory;

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * @var DeployDefinitionFileFinder
     */
    private $deployDefinitionFileFinder;

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param DispatcherFinder $dispatcherFinder
     */
    public function setDispatcherFinder(DispatcherFinder $dispatcherFinder)
    {
        $this->dispatcherFinder = $dispatcherFinder;
    }

    /**
     * @param DeployFactory $deployFactory
     */
    public function setDeployFactory(DeployFactory $deployFactory)
    {
        $this->deployFactory = $deployFactory;
    }

    /**
     * @param EventDispatcher $eventDispatcher
     */
    public function setEventDispatcher(EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param DeployDefinitionFileFinder $deployDefinitionFileFinder
     */
    public function setDeployDefinitionFileFinder(DeployDefinitionFileFinder $deployDefinitionFileFinder)
    {
        $this->deployDefinitionFileFinder = $deployDefinitionFileFinder;
    }

    /**
     * @inheritdoc
     */
    public function configure()
    {
        parent::configure();
        $this->setName('deploy:run');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws \ReflectionException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->eventDispatcher->dispatch(PreRunDeployEvent::class, new PreRunDeployEvent($input));

        $definitions = $this->getExecutableDefinitions($input, $output);
        $output->writeln(sprintf('Executing %s definitions', count($definitions)));
        $success = true;
        $exitCode = 0;

        foreach($definitions as $className){
            if(!class_exists($className) || !in_array(DeployDefinitionInterface::class, class_implements($className), false)){
                $output->write(sprintf(
                    'WARNING: Skipping definition %s since it does not implement %s',
                    $className,
                    DeployDefinitionInterface::class
                ));
                continue;
            }

            $output->writeln(sprintf('Execute definition %s', $className), OutputInterface::VERBOSITY_VERBOSE);
            $deployDefinition = $this->deployFactory->createDeploy($className);
            $event = new PreDispatchDeploymentEvent($deployDefinition);
            $this->eventDispatcher->dispatch(PreDispatchDeploymentEvent::class, $event);

            try {
                if (($exitCode = $this->runDeploymentDefinition($deployDefinition, $output)) !== 0) {
                    throw new FailedException(sprintf('Failed to execute definition %s', $className));
                }
                die("XYZ");
                $output->writeln(sprintf("Finished executing definition %s", $className), OutputInterface::VERBOSITY_DEBUG);
                $this->locator->Events()->getFacade()->dispatchEvent(PostDispatchDeploymentEvent::class, new PostDispatchDeploymentEvent($deployment, true));
            }
            catch(\Exception $e){
                $output->writeln(sprintf('ERROR (%s): %s', get_class($e), $e->getMessage()));
                $success = false;
                $exitCode = 5;
            }
            finally {
                $this->eventDispatcher->dispatch(
                    PostDispatchDeploymentEvent::class,
                    new PostDispatchDeploymentEvent($deployDefinition, $success)
                );
            }

            if($success === false){
                break;
            }
        }

        die('DEBUG Ende');

        $this->locator->Events()->getFacade()->dispatchEvent(PostRunDeploy::class, new PostRunDeploy($success));

        exit($exitCode);
    }

    /**
     * @param DeployDefinitionInterface $deployDefinition
     * @param OutputInterface $output
     * @throws FailedException
     * @return int
     */
    private function runDeploymentDefinition(DeployDefinitionInterface $deployDefinition, OutputInterface $output): int
    {
        $return = 0;
        $deployDefinition->define();

        foreach($deployDefinition->getTaskDefinitions()->toArray() as $taskDefinition){
            $output->writeln(
                sprintf('Executing %s => %s', get_class($deployDefinition), get_class($taskDefinition)),
                OutputInterface::VERBOSITY_DEBUG
            );

            $result = $this->runTaskDefinition($taskDefinition, $output);

            if($result->getExitCode() > 0){
                $return = $result->getExitCode();
                break;
            }
        }

        return $return;
    }

    /**
     * @param TaskDefinitionInterface $taskDefinition
     * @param OutputInterface $output
     * @return DispatchResultInterface
     * @throws \Deployee\Plugins\Deploy\Exception\DispatcherException
     */
    private function runTaskDefinition(TaskDefinitionInterface $taskDefinition, OutputInterface $output): DispatchResultInterface{

        $event = new PreDispatchTaskEvent($taskDefinition);
        $this->eventDispatcher->dispatch(PreDispatchTaskEvent::class, $event);

        if($event->isPreventDispatch() === true){
            return new DispatchResult(0, 'Skipped execution of task definition', '');
        }

        $finder = new DispatcherFinder($this->container);
        $dispatcher = $finder->findTaskDispatcherByDefinition($taskDefinition);
        $result = $dispatcher->dispatch($taskDefinition);

        if($result->getExitCode() > 0){
            $output->write(
                sprintf(
                    "Error while executing task (%s)" . PHP_EOL . "Output: %s" . PHP_EOL . "Error output: %s",
                    $result->getExitCode(),
                    $result->getOutput(),
                    $result->getErrorOutput()
                )
            );
        }

        if($result->getOutput()) {
            $output->writeln($result->getOutput(), OutputInterface::VERBOSITY_VERBOSE);
        }
die('DISPATCHED');
        $this->locator->Events()->getFacade()->dispatchEvent(PostDispatchTaskEvent::class, new PostDispatchTaskEvent($taskDefinition, $result));

        return $result;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return \ArrayObject
     */
    private function getExecutableDefinitions(InputInterface $input, OutputInterface $output): \ArrayObject
    {
        $event = new FindExecutableDefinitionFilesEvent($this->deployDefinitionFileFinder->find(), $input, $output);

        $this->eventDispatcher->dispatch(FindExecutableDefinitionFilesEvent::class, $event);

        /* @var ClassLoader $classLoader */
        $classLoader = require('vendor/autoload.php');
        $definitionFileCollection = $event->getDefinitionFileCollection();
        $classLoader->addClassMap($definitionFileCollection->getArrayCopy());

        return new \ArrayObject(array_keys($definitionFileCollection->getArrayCopy()));
    }
}