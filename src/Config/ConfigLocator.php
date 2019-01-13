<?php


namespace Deployee\Config;


class ConfigLocator
{
    /**
     * @var array
     */
    private static $possibleConfigFileNames = [
        '.deplyoee.yml',
        'deployee.yml',
        '.deployee.dist.yml',
        'deployee.dist.yml',
    ];

    /**
     * @param array $searchableDirs
     * @return string
     */
    public function locate(array $searchableDirs): string
    {
        foreach ($searchableDirs as $expectedDir) {
            foreach(self::$possibleConfigFileNames as $possibleConfigFileName){
                $expectedFilepath = $expectedDir . DIRECTORY_SEPARATOR . $possibleConfigFileName;
                if(is_file($expectedFilepath)){
                    return realpath($expectedDir) . DIRECTORY_SEPARATOR;
                }
            }
        }

        throw new \RuntimeException(
            sprintf(
                'Could not find config file (%s)',
                implode(', ', self::$possibleConfigFileNames)
            )
        );
    }
}