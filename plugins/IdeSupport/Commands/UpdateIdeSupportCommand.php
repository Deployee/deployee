<?php

namespace Deployee\Plugins\IdeSupport\Commands;


use Deployee\Plugins\Deploy\Helper\TaskCreationHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateIdeSupportCommand extends Command
{
    /**
     * @var TaskCreationHelper
     */
    private $taskCreationHelper;

    /**
     * @param TaskCreationHelper $taskCreationHelper
     */
    public function setTaskCreationHelper(TaskCreationHelper $taskCreationHelper)
    {
        $this->taskCreationHelper = $taskCreationHelper;
    }

    /**
     * @inheritdoc
     */
    public function configure()
    {
        parent::configure();
        $this->setName('ide-support:generate');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws \ReflectionException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $definitionSupport = $this->generateDeploymentDefinitionSupportClass();

        $targetFile = getcwd() . '/.deployee_ide_helper.php';
        $contents = <<<EOL
<?php
{$definitionSupport}

EOL;

        file_put_contents($targetFile, $contents);
        $output->writeln(sprintf("Generated helper classes to file %s", $targetFile));
    }

    /**
     * @return string
     * @throws \ReflectionException
     */
    private function generateDeploymentDefinitionSupportClass()
    {
        $alias = $this->taskCreationHelper->getAllAlias();
        $helperMethods = [];

        foreach($alias as $helperName => $aliasDefinition){
            $methodSignatur = implode(", ", $this->getClassConstructorSignatur($aliasDefinition['class']));
            $instanciationSignatur = implode(", ", $this->getClassConstructorSignatur($aliasDefinition['class'], false));
            $helperMethods[] = <<<EOL
    /**
     * @return {$aliasDefinition['class']}
     */
    public function {$aliasDefinition['alias']}({$methodSignatur})
    {
        return new {$aliasDefinition['class']}({$instanciationSignatur});
    }
EOL;
        }

        $helperMethods = implode(PHP_EOL . PHP_EOL, $helperMethods);
        $date = date('d.m.Y, H:i:s');
        return <<<EOL
/**
 * This class was generated on {$date}
 */
abstract class GeneratedDeployeeIdeSupportDefinitions
{
    {$helperMethods}
}
EOL;
    }

    /**
     * @param string $className
     * @param bool $includeTypeHins
     * @return array
     * @throws \ReflectionException
     */
    private function getClassConstructorSignatur($className, $includeTypeHins = true)
    {
        $refection = new \ReflectionClass($className);
        if(!$refection->getConstructor()){
            return [];
        }

        $signatur = [];
        /* @var \ReflectionParameter $parameter */
        foreach($refection->getConstructor()->getParameters() as $parameter){
            $defaultValue = "";

            if($parameter->isOptional()){
                $defaultValue = var_export($parameter->getDefaultValue(), true);
            }

            $signatur[] = trim(sprintf(
                '%s $%s%s',
                $includeTypeHins && $parameter->isArray() ? 'array' : '',
                $parameter->getName(),
                $parameter->isOptional() ? " = {$defaultValue}" : ''
            ));
        }

        return $signatur;
    }
}