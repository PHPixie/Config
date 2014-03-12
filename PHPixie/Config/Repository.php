<?php

namespace PHPixie\Config;

abstract class Repository{
    protected $loaders;
    
    public function __construct($loaders)
    {
        $this->loaders = $loaders;
    }
    
    public abstract function get($name);
}