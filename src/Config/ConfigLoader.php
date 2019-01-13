<?php


namespace Deployee\Config;


use Symfony\Component\Yaml\Yaml;

class ConfigLoader
{
    public function load(string $filepath)
    {
        if(!is_file($filepath) || !($fileContents = file_get_contents($filepath))){
            throw new \RuntimeException(sprintf('Could not open %s', $filepath));
        }

        $contents = Yaml::parse($fileContents);
        return new Config($contents);
    }
}