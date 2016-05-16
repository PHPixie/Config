<?php

namespace PHPixie\Config;

class Storages
{
    protected $configBuilder;
    protected $slice;
    
    public function __construct($configBuilder, $slice)
    {
        $this->configBuilder = $configBuilder;
        $this->slice = $slice;
    }
    
    public function file($file, $parameters = null)
    {
        return new \PHPixie\Config\Storages\Type\File(
            $this->slice,
            $this->configBuilder->formats(),
            $file,
            $parameters
        );
    }
    
    public function directory($directory, $name, $defaultFormat = 'php', $parameters = null)
    {
        return new \PHPixie\Config\Storages\Type\Directory(
            $this,
            $this->slice,
            $directory,
            $name,
            $defaultFormat,
            $parameters
        );
    }
}