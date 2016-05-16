<?php

namespace PHPixie;

class Config
{
    protected $builder;
    
    public function __construct($slice)
    {
        $this->builder = $this->buildBuilder($slice);
    }
    
    public function file($file, $parameters = null)
    {
        return $this->builder->storages()->file($file, $parameters);
    }
    
    public function directory($directory, $name, $defaultFormat = 'php', $parameters = null)
    {
        return $this->builder->storages()->directory(
            $directory,
            $name,
            $defaultFormat,
            $parameters
        );
    }
    
    public function builder()
    {
        return $this->builder;
    }
    
    protected function buildBuilder($slice)
    {
        return new Config\Builder($slice);
    }
}
