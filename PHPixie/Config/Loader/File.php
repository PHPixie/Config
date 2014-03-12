<?php

namespace PHPixie\Config\Loader;

class File extends PHPixie\Config\Loader {
    protected $handler;
    
    public function __construct($config, $handler)
    {
        $this->handler = $handler;
        parent::__construct($config);
    }
    
    public function persist()
    {
        $this->handler->write($this->file, $this->data);
    }
    
    public function load()
    {
        $this->data = $this->handler->read($this->file);
    }
}