<?php

namespace PHPixie\Config\Storages\Type;

class File extends    \PHPixie\Slice\Data\Implementation
           implements \PHPixie\Config\Storages\Storage\Editable\Persistable
{
    protected $formats;
    protected $file;
    protected $parameters;
    
    protected $arrayData;
    protected $format;
    
    protected $isLoaded   = false;
    protected $isModified = false;

    public function __construct($sliceBuilder, $formats, $file, $parameters = null)
    {
        $this->formats    = $formats;
        $this->file       = $file;
        $this->parameters = $parameters;
        parent::__construct($sliceBuilder);
    }

    public function getData($path = null, $isRequired = false, $default = null)
    {
        $data = $this->arrayData()->getData($path, $isRequired, $default);
        if($this->parameters === null) {
            return $data;
        }
        
        if(is_string($data)) {
            return $this->checkParameter($data);
        }
        
        if(is_array($data)) {
            array_walk_recursive($data, array($this, 'checkParameter'));
        }
        
        return $data;
    }
    
    protected function checkParameter(&$value)
    {
        if($value && is_string($value) && $value{0} == '%') {
            $length = strlen($value);
            if($value{$length - 1} == '%') {
                $value = $this->parameters->getRequired(substr($value, 1, $length-2));
            }
        }
        
        return $value;
    }

    public function set($path, $value)
    {
        $this->arrayData()->set($path, $value);
        $this->isModified = true;
    }

    public function remove($path = null)
    {
        $this->arrayData()->remove($path);
        $this->isModified = true;
    }
    
    public function keys($path = null, $isRequired = false)
    {
        return $this->arrayData()->keys($path, $isRequired);
    }
    
    public function slice($path = null)
    {
        return $this->arrayData()->slice($path);
    }
    
    public function arraySlice($path = null)
    {
        return $this->arrayData()->arraySlice($path);
    }
    
    public function getIterator()
    {
        return $this->arrayData()->getIterator();
    }

    public function persist($removeIfEmpty = false)
    {
        if (!$this->isModified)
            return;

        $data = $this->get(null, array());
        
        if(empty($data) && $removeIfEmpty && file_exists($this->file)) {
            unlink($this->file);
            
        }else{
            $this->format()->write($this->file, $data);
        
        }
        
        $this->isModified = false;
    }

    protected function arrayData()
    {
        if($this->arrayData === null) {
            
            if(file_exists($this->file)) {
                $data = $this->format()->read($this->file);
            }else{
                $data = array();
            }
            
            $this->arrayData = $this->sliceBuilder->editableArrayData($data);
        }
        
        return $this->arrayData;
    }
    
    protected function format()
    {
        if($this->format === null) {
            $this->format = $this->formats->getByFilename($this->file);
        }
        return $this->format;
    }
}
