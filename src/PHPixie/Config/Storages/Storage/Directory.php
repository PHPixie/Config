<?php

namespace PHPixie\Config\Storages\Storage;

class Directory {
    
    protected $config;
    protected $directory;
    protected $rootName;
    protected $fileLoaders = array();
    protected $directoryTree;
    
    public function __construct($config, $directory, $rootName = 'config')
    {
        $this->config = $config;
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
        array_pop($path);
        $loader = $this->getNearestLoader($path);
        $key = $this->getKeyOffset($key, $loader->key());
        print_r($key); die;
        $args = func_get_args();
        if (array_key_exists(1, $args))
            return $loader->slice($key, $args[1]);
        return $loader->get($key);
    }
    
   
    protected function getNearestLoader($path)
    {
        return $this->findLoaderRecursive($path, $this->directory, $this->directoryTree);
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
                $branch[$fileName] = $filePath;
            }
        }
        
        return $branch;
    }
    
    protected function findLoaderRecursive($path, $directory, &$branch, $key = null)
    {
        $current = array_shift($path);
        
        if ($branch === null)
            $branch = $this->readDirectory($directory);
        print_r($branch);
        $currentPath[] = $current;
        if (is_array($branch[$current])) {
            $loader = $this->findLoaderRecursive($path, $directory.'/'.$current, $branch[$current]);
            if ($loader !== null)
                return $loader;
        }
        
        if ($branch[$current] instanceof \PHPixie\Config\Storages\Storage\File)
            return $branch[$current];
        
        if (is_string($branch[$current]))
            return $branch[$current] = $this->getFileLoader($branch[$current], implode('.', $currentPath));
        return null;
    }
    
    protected function getFileLoader($file, $key)
    {
        return $this->config->fileStorage($file, $key);
    }
    
    protected function getKeyOffset($key, $childKey)
    {
        return substr($key, strlen($childKey) + 1);
    }
}