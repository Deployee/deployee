<?php

namespace Deployee\Plugins\Install\Commands;

use Deployee\Components\Application\Command;
use Deployee\Components\Config\ConfigInterface;
use Deployee\Kernel\KernelConstraints;
use Deployee\Plugins\Install\Events\RunInstallCommandEvent;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

class InstallCommand extends Command
{
    /**
     * @inheritdoc
     */
    public function configure()
    {
        parent::configure();
        $this->setName('install');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Running install');

        /* @var ConfigInterface $config */
        $config = $this->container->get(ConfigInterface::class);
        $path = $config->get('deploy_definition_path', 'definitions');
        $path = strpos($path, '/') !== 0 && strpos($path, ':') !== 1
            ? $this->container->get(KernelConstraints::WORKDIR) . DIRECTORY_SEPARATOR . $path
            : $path;

        if(!is_dir($path)){
            $output->writeln(sprintf('Directory %s does not exist', $path));
            exit(255);
        }

        /* @var EventDispatcher $dispatcher */
        $dispatcher = $this->container->get(EventDispatcher::class);
        $dispatcher->dispatch(RunInstallCommandEvent::class, new RunInstallCommandEvent());

        $output->writeln('Finished installing');
    }
}