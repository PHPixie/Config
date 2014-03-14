<?php

namespace PHPixie\Config\Storages\Storage\File;

class Handlers
{
    protected $handlers = array();
    
    protected $extensionHandlers = array(
        'php' => 'PHP'
    );
    
    public function getForExtension($extensionName)
    {
        $name = $this->extensionHandlers[$extensionName];
        if(!isset($this->handlers[$name]))
            $this->handlers[$name] = $this->buildHandler($name);
        return $this->handlers[$name];
    }
    
    protected function buildHandler($name)
    {
        $class = '\PHPixie\Config\Storages\Storage\File\Handler\\'.$name;
        return new $class;
    }
}