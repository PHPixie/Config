<?php

namespace PHPixie;

class Config{
    
    protected $fileHandlers;
    
    public function fileStorage($file, $key = null)
    {
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        $handler = $this->fileHandlers()->getForExtension($extension);
        return new \PHPixie\Config\Storages\Storage\File($this, $handler, $file, $key);
    }
    
    public function fileHandlers()
    {
        if (!isset($this->fileHandlers))
            $this->fileHandlers = $this->buildFileHandlers();
        
        return $this->fileHandlers;
    }
    
    protected function buildFileHandlers()
    {
        return new \PHPixie\Config\Storages\Storage\File\Handlers($this);
    }
}