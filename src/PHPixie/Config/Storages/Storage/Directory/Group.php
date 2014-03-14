<?php

namespace PHPixie\Config\Storages\Storage\Directory;

class Group
{
    protected $directory;
    protected $name;
    protected $fileExists = false;
    protected $storage;
    protected $groups;
    
    public function __constructor($directory, $name, $fileExtension = 'php')
    {
        $this->directory = $directory;
        $this->name = $name;
    }
    
    public function getGroups()
    {
        return $this->groups;
    }
    
    protected function getStorage($path, $createMissing = true)
    {
        if ($this->groups === null)
            $this->populateGroups();
        
        if (!empty($path)){
            $key = array_shift($path);
            if (isset($this->groups[$name]))
                return $this->groups[$name]->getStorage($path);
        }
        
        return $this->storage($createMissing);
    }
    
    protected function populateGroups()
    {
        $this->groups = array();
        $directory = $this->directory.'/'.$this->name.'/';
        if(is_dir($directory)){
            foreach(scandir($directory) as $file) {
                if ($file === '.' || $file === '..')
                    continue;
            
                $filePath = $this->directory.'/'.$file;
                if (is_dir($filePath)) {
                    $group = $this->group($directory, $file, 
                }
                $fileName = is_dir($filePath) ? $file : pathinfo($filePath, PATHINFO_FILENAME);
                $this->groups[$fileName] = $this->group($directory, $file);
            }
        }
    }
    
    protected function storage($createMissing = false)
    {
        if (!isset($this->storage)) {
            $file = $this->directory
            if (!file_exists($file))
                return null;
        }
        
        return $storage;
            
    }
}