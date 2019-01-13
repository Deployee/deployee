<?php

namespace Deployee\Plugins\Deploy\Commands;

use Deployee\Components\Application\Command;
use Deployee\Components\Config\ConfigInterface;
use Deployee\Kernel\KernelConstraints;
use Deployee\Plugins\RunDeploy\Events\RunInstallCommandEvent;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
        $path = $config->get('definition_path', 'migrations');
        $path = (strpos($path, '/') !== 0)
            ? $this->container->get(KernelConstraints::WORKDIR) . DIRECTORY_SEPARATOR . $path
            : $path;

        if(!is_dir($path)){
            $output->writeln(sprintf('Directory %s does not exist', $path));
            exit(255);
        }

        die("TEST");

        $event = new RunInstallCommandEvent();
        $this->locator->Events()->getFacade()->dispatchEvent(RunInstallCommandEvent::class, $event);

        $output->writeln('Finished installing');
    }
}