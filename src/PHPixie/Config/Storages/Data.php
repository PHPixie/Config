<?php

namespace PHPixie\Config\Storages;

class Data extends \PHPixie\Config\Storage{

    protected $config;
    protected $data;
    
    public function __construct($config, $data = array(), $key = null)
    {
        $this->data = $data;
        parent::__construct($config, $key);
    }
    
    public function set($key, $value)
    {
        if ($key === null) {
            if (!is_array($value))
                throw new \PHPixie\Config\Exception("Only array values can be set as root.");
            $this->data = $value;
            return;
        }
        
        $path = explode('.', $key);
        $key = array_pop($path);
        $group = &$this->findGroup($path, true);
        $group[$key] = $value;
    }
    
    public function remove($key = null)
    {
        if ($key === null) {
            $this->data = array();
            return;
        }
        
        $path = explode('.', $key);
        $key = array_pop($path);
        $group = &$this->findGroup($path, true);
        unset($group[$key]);
    }
    
    public function get($key = null) {
        if ($key !== null) {
            $path = explode('.', $key);
            $key = array_pop($path);
            $group = & $this->findGroup($path);
            if ($group !== null && array_key_exists($key, $group))
                return $group[$key];
        }elseif(!empty($this->data))
            return $this->data;
        
        $args = func_get_args();
        if (array_key_exists(1, $args))
            return $args[1];
        
        throw new \PHPixie\Config\Exception("Configuration for '{$this->fullKey($key)}' not set.");
    }
    
    protected function &findGroup($path, $createMissing = false) {
        $group = &$this->data;
        $count = count($group);
        foreach ($path as $i => $key) {
            
            if (!array_key_exists($key, $group)) {
                if(!$createMissing) {
                    $ret = null;
                    return $ret;
                }
                
                $group[$key] = array();
            }
            
            if (!is_array($group[$key])) {
                if(!$createMissing) {
                    $ret = null;
                    return $ret;
                }
                
                $key = $this->fullKey(implode('.', $path));
                throw new \PHPixie\Config\Exception("An element with key '$key' is not an array.");
            }
            
            $group = &$group[$key];
        }
        
        return $group;
    }
    
}
