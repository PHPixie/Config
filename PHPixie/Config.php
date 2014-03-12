<?php

namespace PHPixie;

class Config{
    protected $loaders = array();
    protected $repositories = array();
    
    public function registerRepository($repository)
    {
        $this->repositories[] = $repository;
    }
    
    public function slice($key) {
        $path = explode('.', $key, 2);
        $loader = $this->requireLoader($path[0]);
        if ($loader === null)
            $this->throwLoaderException();
        return $loader->slice($path[1]);
    }
    
    protected function throwLoaderException()
    {
        throw new \PHPixie\Config\Exception("Configuration for '{$path[0]}' could not be loaded.");
    }
    
    public function getLoader($name)
    {
        if (!isset($this->loaders[$name])) {
            foreach($repositories as $repository) {
                if (($loader = $repository->get($name)) !== null) {
                    $this->loaders[$name] = $loader;
                    break;
                }
            }
            
            if (!isset($this->loaders[$name]))
                return null;
            
        }
        
        return $this->loaders[$name];
    }
}