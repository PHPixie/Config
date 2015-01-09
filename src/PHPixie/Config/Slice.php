<?php

namespace PHPixie\Config;

abstract class Slice
{
    protected $config;
    protected $key;

    public function __construct($config, $key)
    {
        $this->config = $config;
        $this->key = $key;
    }

    public function get($key = null, $default = null)
    {
        return $this->getData($key, false, $default);
    }
    
    public function getRequired($key = null)
    {
        return $this->getData($key, true);
    }
    
    public function key()
    {
        return $this->key;
    }

    public function fullKey($key = null)
    {
        if($this->key === null)

            return $key;

        if ($key === null)
            return $this->key;

        return $this->key.'.'.$key;
    }
    
    abstract public function slice($key = null);
    abstract public function set($key, $value);
    abstract public function remove($key = null);
    abstract public function keys($key = null, $isRequired = false);
    abstract public function getData($key = null, $isRequired = false, $default = null);
}
