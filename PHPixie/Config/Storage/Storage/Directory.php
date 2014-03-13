<?php

namespace PHPixie\Config\Loader;

class Directory {
    
    protected $directory;
    protected $rootName;
    protected $fileLoaders = array();
    protected $directoryTree;
    
    public function __construct($directory, $rootName = 'config')
    {
        $this->directory = $directory;
        $this->rootName = $rootName;
    }
    
    public function slice($key) {
        $path = explode('.', $key);
        $key = array_pop($path);
        $loader = $this->getNearestLoader($path);
        $this->getKeyOffset($key, $loader->key());
        return $loader->slice($key);
    }
    
    public function get($key) {
        $path = explode('.', $key);
        $key = array_pop($path);
        $loader = $this->getNearestLoader($path);
        $this->getKeyOffset($key, $loader->key());
        $args = func_get_args();
        if (array_key_exists(1, $args))
            return $loader->slice($key, $args[1]);
        return $loader->get($key);
    }
    
   
    protected function getNearestLoader($path)
    {
        return $this->findLoaderRecursive($path, $this->directory, $this->data);
    }
    
    protected function readDirectory($directory)
    {
        $branch = array();
        
        foreach(scandir($directory) as $file) {
            if ($file === '.' || $file === '..')
                continue;
            
            $filePath = $directory.'/'.$file;
            
            if (is_dir($filePath)) {
                $branch[$file] = null;
            }else {
                $fileName = pathinfo($filePath, PATHINFO_FILENAME);
                $branch[$fileName] = $file;
            }
        }
        
        return $branch;
    }
    
    protected function findLoaderRecursive($path, $directory, & $branch, $key = null)
    {
        $current = array_unshift($path);
        
        if ($branch === null) {
            $branch = $this->readDirectory($directory);
        
        $currentPath[] = $current;
        
        if (is_array($branch[$current])) {
            $loader = $this->findLoaderRecursive($path, $directory.'/'.$current, $branch[$current]);
            if ($loader !== null)
                return $loader;
        }
        
        if ($branch[$current] isnatnceof \PHPixie\Config\Loader)
            return $branch[$current];
        
        if (is_string($branch[$current]))
            return $branch[$current] = $this->getFileLoader(implode('.', $currentPath), $file);
        return null;
    }
}