<?php

namespace PHPixie\Config\Storages;

abstract class Slice {
    
    protected $storage;
    protected $key;
    
    public function __construct($storage, $key)
    {
        $this->storage = $storage;
        $this->key = $key;
    }
    
    public function get($key = null) {
        $key = $this->fullKey($key)
        $args = func_get_args();
        
        if (array_key_exists(1, $args))
            return $this->storage->get($key, $args[1]);
        
        return $this->storage->get($key);
    }
    
    public function slice($key = null) {
        return $this->storage->slice($this->fullKey($key));
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