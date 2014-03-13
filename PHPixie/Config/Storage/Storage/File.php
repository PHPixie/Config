<?php

namespace PHPixie\Config\Storages\Storage;

class File extends Data {
    
    protected $handler;
    protected $loaded;
    
    public function __construct($config, $handler)
    {
        $this->handler = $handler;
        parent::__construct($config);
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