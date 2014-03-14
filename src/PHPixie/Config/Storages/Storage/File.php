<?php

namespace PHPixie\Config\Storages\Storage;

class File extends Data {
    
    protected $handler;
    protected $loaded;
    protected $key;
    
    public function __construct($config, $handler, $key = null)
    {
        $this->handler = $handler;
        $this->key = $key;
        $this->config = $config;
    }
    
    public function slice($key) {
        $this->requireLoad();
        parent::slice($key);
    }
    
    public function get($key) {
        $this->requireLoad();
        parent::get($key);
    }
    
    public function set($key, $value) {
        $this->requireLoad();
        parent::set($key, $value);
    }
    
    public function persist()
    {
        $this->handler->write($this->file, $this->data);
    }
    
    public function key()
    {
        return $this->key;
    }
    
    protected function load()
    {
        $this->data = $this->handler->read($this->file);
    }
    
    protected function requireLoad() 
    {
        if (!$this->loaded) {
            $this->load();
            $this->loaded = true;
        }
    }
}