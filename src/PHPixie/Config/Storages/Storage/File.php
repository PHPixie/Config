<?php

namespace PHPixie\Config\Storages\Storage;

class File extends Data {
    
    protected $handler;
    protected $loaded;
    protected $file;
    protected $key;
    
    public function __construct($config, $handler, $file, $key = null)
    {
        $this->config = $config;
        $this->handler = $handler;
        $this->file = $file;
        $this->key = $key;
    }
    
    public function slice($key) {
        $this->requireLoad();
        return parent::slice($key);
    }
    
    public function get($key) {
        $this->requireLoad();
        return parent::get($key);
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