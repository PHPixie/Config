<?php

namespace PHPixie\Config\Storages\Type;

class File extends    \PHPixie\Slice\Data\Implementation
           implements \PHPixie\Config\Storages\Storage\Editable\Persistable
{
    protected $formats;
    protected $file;
    
    protected $arrayData;
    protected $format;
    
    protected $isLoaded   = false;
    protected $isModified = false;

    public function __construct($sliceBuilder, $formats, $file)
    {
        $this->formats = $formats;
        $this->file    = $file;
        parent::__construct($sliceBuilder);
    }

    public function getData($path = null, $isRequired = false, $default = null)
    {
        return $this->arrayData()->getData($path, $isRequired, $default);
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
            
            $this->arrayData = $this->sliceBuilder->arrayData($data);
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