<?php

namespace PHPixie\Config\Storages\File;

class Handlers
{
    protected $handlers = array();

    protected $extensionHandlers = array(
        'php' => 'PHP'
    );

    public function getForExtension($extensionName)
    {
        $name = $this->extensionHandlers[$extensionName];

        return $this->get($name);
    }

    public function get($name)
    {
        if(!isset($this->handlers[$name]))
            $this->handlers[$name] = $this->buildHandler($name);

        return $this->handlers[$name];
    }

    protected function buildHandler($name)
    {
        $class = '\PHPixie\Config\Storages\File\Handler\\'.$name;

        return new $class;
    }
}
