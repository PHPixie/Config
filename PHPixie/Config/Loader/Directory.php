<?php

namespace PHPixie\Config\Loader;

class Directory {
    
    protected $directory;
    protected $rootName;
    protected $fileLoaders = array();
    protected $directoryTree = array();
    
    public function __construct($directory, $rootName = 'config')
    {
        $this->directory = $directory;
        $this->rootName = $rootName;
    }
    
    protected function slice($key) {
        $path = explode('.', $key);
        $key = array_pop($path);
        $loader = $this->getNearestLoader($path);
        $key = implode('.', array_diff($path, $loader->key());
        if (empty($key))
            $key = null;
        return $loader->slice($key);
    }
    
    protected function getNearestLoader($path)
    {
        array_unshift($path, $this->rootName);
        $directory = $this->directory;
        
        foreach($path as $step) {
            $files = array();
            foreach(scandir($directory) as $file) {
            if ($file === '.' || $file === '..')
                continue;
            
            $this->files[pathinfo($file, PATHINFO_FILENAME] = $file;
        }
        
        }
        
    }
    
    public function get($name){
        $files = $this->files();
        if (!isset($files[$name]))
            return null;
        
        return $this->loaders->file($this->directory.'/'.$files[$name]);
    }
    
    protected function files()
    {
        if ($this->files === null) {
            $this->files = array();
            
        }
        
        return $this->files;
    }
}