<?php

namespace PHPixie;

class Config
{
    protected $builder;
    
    public function __construct($slice)
    {
        $this->builder = new Config\Builder($slice);
    }
    
    public function file($file)
    {
        return $this->builder->storages()->file($file);
    }
    
    public function directory($directory, $name, $defaultFormat = 'php')
    {
        return $this->builder->storages()->directory(
            $directory,
            $name,
            $defaultFormat
        );
    }
}
