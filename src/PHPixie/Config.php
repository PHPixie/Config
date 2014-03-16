<?php

namespace PHPixie;

class Config{
    
    protected $fileHandlers;

    public function dataStorage($data = array(), $key = null)
    {
        return new \PHPixie\Config\Storages\Data($this, $data, $key);
    }
    
    public function fileStorage($file, $key = null)
    {
        $dataStorage = $this->dataStorage();
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        $handler = $this->fileHandlers()->getForExtension($extension);
        return new \PHPixie\Config\Storages\File($this, $dataStorage, $handler, $file, $key);
    }
    
    public function directoryStorage($directory, $name, $extension = 'php', $key = null)
    {
        return new \PHPixie\Config\Storages\Directory($this, $directory, $name, $extension, $key);
    }
    
    public function buildSlice($storage, $key = null)
    {
        return new \PHPixie\Config\Storage\Slice($this, $storage, $key);
    }
    
    public function fileHandlers()
    {
        if (!isset($this->fileHandlers))
            $this->fileHandlers = $this->buildFileHandlers();
        
        return $this->fileHandlers;
    }
    
    protected function buildFileHandlers()
    {
        return new \PHPixie\Config\Storages\File\Handlers($this);
    }
}