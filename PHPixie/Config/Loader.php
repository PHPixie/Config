<?php

namespace PHPixie\Config;

abstract class Loader{

    protected $config;
    protected $data;
    protected $loaded = false;
    
    public function __construct($config)
    {
        $this->config = $config;
    }
    
    public function set($key, $value)
    {
        $this->requireLoad();
        $path = explode('.', $key);
        $key = array_pop($path);
        $group = &$this->findGroup($path, true);
        $group[$key] = $value;
    }
    
    public function get($key = null) {
        $this->requireLoad();
        if ($key === null)
            return $this->data;
        
        $path = explode('.', $key);
        $key = array_pop($path);
        $group = &$this->findGroup($path);
        
        if ($group !== null && array_key_exists($key, $group);
            return $group[$key];
        
        $args = func_get_args();
        if (array_key_exists(1, $args))
            return $args[1];
        
        throw new \PHPixie\Config\Exception("Configuration for '$key' not set.");
    }
    
    public function slice($key = null) {
        $this->requireLoad();
        $path = explode('.', $key);
        $key = array_pop($path);
        $group = &$this->findGroup($path);
        if($group === null)
            throw new \PHPixie\Config\Exception("Configuration for '$key' not set.");
        $this->config->buildSlice($this, $key);
    }
    
    protected function &findGroup($path, $createMissing = false) {
        $group = &$this->data;
        foreach ($path as $i => $key) {
            
            if ($i === $count - 1)
                return array(true, $this->data[$key]);
            
            if (!array_key_exists($key, $group) || ()) {
                if(!$createMissing) {
                    $group = null;
                    break;
                }
                
                $group[$key] = array();
            }
            
            if (!is_array($group[$key])) {
                if(!$createMissing) {
                    $group = null;
                    break;
                }
                
                throw new \PHPixie\Config\Exception("An element with key {implode('.', $path)} is not an array.");
            }
            
            $group = &$group[$key];
        }
        
        return $group;
    }
    
    protected function requireLoad() 
    {
        if (!$this->loaded) {
            $this->load();
            $this->loaded = true;
        }
    }
    public abstract function load();
    public abstract function persist();
}
