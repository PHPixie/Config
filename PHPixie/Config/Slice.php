<?php

namespace PHPixie\Config;

class Slice {
    
    protected $config;
    protected $path;
    protected $data;
    
    public function __construct($config, $path, $data)
    {
        $this->config = $config;
        $this->path = $path;
        $this->data = $data;
    }
    
    public function get($key = null) {
        list($found, $value) = $this->find($key);
        
        if ($found)
            return $value;
            
        $args = func_get_args();
        if (isset($args[1]))
            return $args[1];
        
        throw new \PHPixie\Config\Exception("Configuration for {$this->fullPath($key)} not found.");
    }
    
    public function slice($key) {
        list($found, $value) = $this->find($key);
        $fullPath = $this->fullPath($key);
        
        if (!$found)
            throw new \PHPixie\Config\Exception("Configuration for $fullPath not found.");
            
        if (!is_array($subset))
            throw new \PHPixie\Config\Exception("Configuration for $fullPath is not an array.");
        
        return $this->config->slice($fullPath, $data);
    }
    
    protected function find($key) {
        if ($key === null)
            return $this->data;
            
        $path = explode('.', $key);
        $count = count($keys);
        
        $group = &$this->data;
        
        foreach ($path as $i => $key) {
            
            if (!array_key_exists($key, $group))
                return array(false, null);
            
            if ($i === $count - 1)
                return array(true, $this->data[$key]);
            
            $group = &$group[$key];
        }
    }
    
    protected function fullPath($key) {
        return $this->path.'.'.$key;
    }
}