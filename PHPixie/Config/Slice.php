<?php

namespace PHPixie\Config;

abstract class Slice {
    
    protected $loader;
    protected $key;
    
    public function __construct($loader, $key)
    {
        $this->loader = $config;
        $this->key = $key;
    }
    
    public function get($key = null) {
        $key = $this->fullKey($key)
        $args = func_get_args();
        
        if (array_key_exists(1, $args))
            return $this->loader->get($key, $args[1]);
        
        return $this->loader->get($key);
    }
    
    public function slice($key = null) {
        return $this->loader->slice($this->fullKey($key));
    }
    
    public function key() {
        return $this->key;
    }
    
    protected function fullKey($key) {
        if($this->key === null)
            return $key;
        
        if ($key === null)
            return $this->key;
        
        return $this->key.'.'.$key;
    }
}