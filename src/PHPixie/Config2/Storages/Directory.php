<?php

namespace PHPixie\Config\Storages;

class Directory extends \PHPixie\Config\Storage\Persistable
{
    protected $config;
    protected $directory;
    protected $name;
    protected $extension;
    protected $storage;
    protected $subdirs;

    public function __construct($config, $directory, $name, $extension = 'php', $key = null)
    {
        $this->directory = $directory;
        $this->name = $name;
        $this->extension = $extension;
        parent::__construct($config, $key);
    }

    public function getData($key = null, $isRequired = false, $default = null)
    {
        $this->requireSubdirs();

        if (!empty($key)) {
            list($storage, $key) = $this->getStorageAndKey($key);
            
            return $storage->getData($key, $isRequired, $default);
        }

        $data = $this->storage()->get();
        foreach ($this->subdirs as $name => $subdir) {
            $subdata = $subdir->get();
            if (!empty($subdata)) {
                $data[$name] = $subdata;
            }
        }

        if (empty($data)) {
            if (!$isRequired)
                return $default;

            throw new \PHPixie\Config\Exception("Configuration for {$key} not set.");
        }

        return $data;
    }

    public function keys($key = null, $isRequired = false)
    {
        $this->requireSubdirs();
        if (!empty($key)) {
            list($storage, $key) = $this->getStorageAndKey($key);
            
            return $storage->keys($key, $isRequired);
        }
        
        $keys = array_fill_keys($this->storage()->keys(), true);
        
        foreach($this->subdirs as $name => $subdir) {
            $keys[$name]=true;
        }
        
        return array_keys($keys);
    }
    
    protected function getStorageAndKey($key)
    {
        list($current, $subkey) = $this->splitKey($key);

        if (isset($this->subdirs[$current])) {
            $storage = $this->subdirs[$current];
            $key = $subkey;
        }else {
            $storage = $this->storage();
        }
        
        return array($storage, $key);
    }
    
    public function set($key, $value)
    {
        $this->requireSubdirs();
        if (!empty($key)) {
            list($current, $subkey) = $this->splitKey($key);
            if (isset($this->subdirs[$current]))
                return $this->subdirs[$current]->set($subkey, $value);

        } elseif (is_array($value)) {
            foreach ($this->subdirs as $name => $subdir) {
                if (array_key_exists($name, $value)) {
                    $subdir->set(null, $value[$name]);
                    unset($value[$name]);
                }else
                    $subdir->remove(null);
            }
        }

        return $this->storage()->set($key, $value);
    }

    public function remove($key = null)
    {
        $this->requireSubdirs();
        if (empty($key)) {
            $data = $this->storage()->remove(null);
            foreach ($this->subdirs as $name => $subdir) {
                $subdir->remove(null);
            }

            return;
        }

        list($current, $subkey) = $this->splitKey($key);
        if (isset($this->subdirs[$current]))
            return $this->subdirs[$current]->remove($subkey);

        return $this->storage()->remove($key);
    }

    public function persist()
    {
        $this->requireSubdirs();
        foreach($this->subdirs as $subdir)
            $subdir->persist();
        $this->storage()->persist();
    }

    protected function splitKey($key)
    {
        $splitKey = explode('.', $key, 2);
        if (!isset($splitKey[1]))
            $splitKey[1] = null;

        return $splitKey;
    }

    protected function requireSubdirs()
    {
        if (is_array($this->subdirs))
            return;

        $this->subdirs = array();
        $dirs = array();
        $directory = $this->directory.'/'.$this->name.'/';
        if (is_dir($directory)) {
            foreach (scandir($directory) as $file) {
                if ($file === '.' || $file === '..')
                    continue;

                $filePath = $directory.'/'.$file;
                if (is_dir($filePath)) {
                    $dirs[] = $file;
                    continue;
                }

                $fileInfo = pathinfo($filePath);
                $fileName = $fileInfo['filename'];
                if (isset($this->groups[$fileName]))
                    throw new \PHPixie\Config\Exception("More than one configuration file for {$this->key}.{$fileName} forund.");
                $this->subdirs[$fileName] = $this->directoryStorage($directory, $fileName, $fileInfo['extension']);
            }

            foreach ($dirs as $dir) {
                if (!isset($this->subdirs[$dir]))
                    $this->subdirs[$dir] = $this->directoryStorage($directory, $dir, $this->extension);
            }
        }

    }

    protected function storage()
    {
        if (!isset($this->storage)) {
            $file = $this->directory.'/'.$this->name.'.'.$this->extension;
            $this->storage = $this->fileStorage($file);
        }

        return $this->storage;
    }

    protected function fileStorage($file)
    {
        return $this->config->fileStorage($file);
    }

    protected function directoryStorage($directory, $name, $extension)
    {
        return $this->config->directoryStorage($directory, $name, $extension);
    }

}