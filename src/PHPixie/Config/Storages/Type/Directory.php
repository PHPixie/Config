<?php

namespace PHPixie\Config\Storages\Type;

class Directory extends \PHPixie\Slice\Data\Implementation
                        implements \PHPixie\Config\Storages\Storage\Editable\Persistable
{
    protected $storages;
    protected $directory;
    protected $name;
    protected $defaultFormat;
    protected $parameters;
    
    protected $storage;
    protected $subdirs;

    public function __construct($storages, $sliceBuilder, $directory, $name, $defaultFormat = 'php', $parameters = null)
    {
        $this->storages      = $storages;
        $this->directory     = $directory;
        $this->name          = $name;
        $this->defaultFormat = $defaultFormat;
        $this->parameters    = $parameters;
        parent::__construct($sliceBuilder);
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
    
    public function slice($path = null)
    {
        return $this->sliceBuilder->editableSlice($this, $path);
    }
    
    public function arraySlice($path = null)
    {
        $data = $this->get($path);
        return $this->sliceBuilder->arraySlice($data, $path);
    }
    
    public function getIterator()
    {
        return $this->sliceBuilder->iterator($this);
    }
    
    protected function getStorageAndKey($key)
    {
        list($current, $subkey) = $this->splitKey($key);

        if (array_key_exists($current, $this->subdirs)) {
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
            if (array_key_exists($current, $this->subdirs)) {
                return $this->subdirs[$current]->set($subkey, $value);
            }
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
        if (array_key_exists($current, $this->subdirs)) {
            return $this->subdirs[$current]->remove($subkey);
        }
        
        return $this->storage()->remove($key);
    }

    public function persist($removeFilesIfEmpty = false)
    {
        $this->requireSubdirs();
        foreach($this->subdirs as $subdir) {
            $subdir->persist($removeFilesIfEmpty);
        }
        
        $this->storage()->persist($removeFilesIfEmpty);
    }

    protected function splitKey($key)
    {
        $splitKey = explode('.', $key, 2);
        if (!array_key_exists(1, $splitKey)) {
            $splitKey[1] = null;
        }
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
                
                if (array_key_exists($fileName,$this->subdirs)) {
                    throw new \PHPixie\Config\Exception("More than one file for {$fileName} in {$directory}.");
                }
                
                $this->subdirs[$fileName] = $this->storages->directory(
                    $directory,
                    $fileName,
                    $fileInfo['extension'],
                    $this->parameters
                );
            }

            foreach ($dirs as $dir) {
                if (!array_key_exists($dir, $this->subdirs))
                    $this->subdirs[$dir] = $this->storages->directory(
                        $directory,
                        $dir,
                        $this->defaultFormat,
                        $this->parameters
                    );
            }
        }

    }

    protected function storage()
    {
        if ($this->storage === null) {
            $file = $this->directory.'/'.$this->name.'.'.$this->defaultFormat;
            $this->storage = $this->storages->file($file, $this->parameters);
        }

        return $this->storage;
    }

}
