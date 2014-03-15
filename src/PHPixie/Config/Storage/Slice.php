<?php

namespace PHPixie\Config\Storage;

class Slice extends \PHPixie\Config\Slice{
    
    protected $storage;
    protected $key;
    
    public function __construct($config, $storage, $key = null)
    {
        $this->storage = $storage;
        parent::__construct($config, $key);
    }
    
    public function get($key = null) {
        $key = $this->storageKey($key);
        $args = func_get_args();
        
        if (array_key_exists(1, $args))
            return $this->storage->get($key, $args[1]);
        
        return $this->storage->get($key);
    }
    
    public function slice($key = null) {
        return $this->storage->slice($this->storageKey($key));
    }
    
    public function set($key, $value)
    {
        $this->storage->set($this->storageKey($key), $value);
    }
    
    public function remove($key = null)
    {
        $this->storage->remove($this->storageKey($key));
    }
    
    public function storageKey($key = null)
    {
        return parent::fullKey($key);
    }
    
    public function fullKey($key = null)
    {
        $fullKey = $this->storageKey($key);
        $storageKey = $this->storage->key();
        
        if($fullKey === null)
            return $storageKey;
        
        if ($storageKey === null)
            return $fullKey;
            
        return $storageKey.'.'.$fullKey;
    }
}