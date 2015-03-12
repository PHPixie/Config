<?php

namespace PHPixie;

class Config
{
    protected $builder;
    
    public function __construct($slice)
    {
        $this->builder = $this->buildBuilder($slice);
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
    
    public function builder()
    {
        return $this->builder;
    }
    
    protected function buildBuilder($slice)
    {
        return new Config\Builder($slice);
    }
}
