<?php

namespace PHPixie\Config\Loader\File;

class Formats {
    
    protected $formats = array();
    
    protected $extensionFormats = array(
        'php' => 'PHP'
    )
    
    public function forExtension($extensionName)
    {
        $name = $this->extensionFormats[$extensionName];
        if(!isset($this->formats[$name]))
            $this->formats[$name] = $this->buildFormat($name);
        return $this->formats[$name];
    }
    
    protected function buildFormat($name)
    {
        $class = 'PHPixie\Config\Loader\File\Format\\'.$name;
        return new $class;
    }
}