<?php

namespace PHPixie\Config\Repository;

class Directory extends \PHPixie\Config\Repository{
    
    protected $directory;
    protected $files;
    
    public function __construct($loaders, $directory)
    {
        $this->directory = $directory;
        parent::__construct($loaders);
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
            foreach(scandir($this->directory) as $file) {
                if ($file === '.' || $file === '..')
                    continue;
                $this->files[pathinfo($file, PATHINFO_FILENAME] = $file;
            }
        }
        
        return $this->files;
    }
}