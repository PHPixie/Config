<?php

namespace PHPixie\Config;

class Builder
{
    protected $slice;
    protected $instances = array();
    
    public function __construct($slice)
    {
        $this->slice = $slice;
    }
    
    public function storages()
    {
        return $this->getInstance('storages');
    }
    
    public function formats()
    {
        return $this->getInstance('formats');
    }
    
    protected function getInstance($name)
    {
        if(!array_key_exists($name, $this->instances)) {
            $method = 'build'.ucfirst($name);
            $this->instances[$name] = $this->$method();
        }
        
        return $this->instances[$name];
    }
    
    protected function buildStorages()
    {
        return new Storages($this, $this->slice);
    }
    
    protected function buildFormats()
    {
        return new Formats();
    }
}