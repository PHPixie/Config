<?php

namespace PHPixie\Config;

class Formats
{
    protected $formats = array();
    protected $extensionMap = array(
        'php'  => 'php',
        'json' => 'json'
    );
    
    protected $classMap = array(
        'php'  => '\PHPixie\Config\Formats\Format\PHP',
        'json' => '\PHPixie\Config\Formats\Format\JSON',
    );
    
    public function php()
    {
        return $this->get('php');
    }
    
    public function json()
    {
        return $this->get('json');
    }
    
    public function getByFilename($file)
    {
        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        $name = $this->extensionMap[$extension];
        return $this->get($name);
    }
    
    protected function get($name)
    {
        if(!array_key_exists($name, $this->formats)) {
            $this->formats[$name] = $this->buildFormat($name);
        }
        
        return $this->formats[$name];
    }
    
    protected function buildFormat($name)
    {
        $class = $this->classMap[$name];
        return new $class;
    }
}