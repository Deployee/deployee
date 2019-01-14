<?php

namespace Deployee\Plugins\Deploy\Finder;

use Symfony\Component\Finder\Finder;

class DeployDefinitionFileFinder
{
    const DEPLOY_FILENAME_PATTERN = '/^(DeployDefinition\_|Deploy\_).*\.php$/';

    /**
     * @var string
     */
    private $searchRoot;

    /**
     * @var Finder
     */
    private $finder;

    /**
     * @param string $searchRoot
     */
    public function __construct(string $searchRoot)
    {
        $this->searchRoot = $searchRoot;
        $this->finder = new Finder();
    }

    /**
     * @return \ArrayObject
     */
    public function find(): \ArrayObject
    {
        $this->finder
            ->files()
            ->name(self::DEPLOY_FILENAME_PATTERN)
            ->depth("<= 1")
            ->sort(function(\SplFileInfo $a, \SplFileInfo $b){
                $sortNameA = substr($a->getBasename(), strpos($a->getBasename(), '_')+1);
                $sortNameB = substr($b->getBasename(), strpos($b->getBasename(), '_')+1);
                return strcmp($sortNameA, $sortNameB);
            })
            ->in([$this->searchRoot]);

        $classMap = new \ArrayObject();
        foreach($this->finder as $file){
            $filename = $file->getBasename();
            $className = substr($filename, 0, strrpos($filename, '.'));

            $classMap[$className] = $file->getRealPath();
        }

        return $classMap;
    }
}