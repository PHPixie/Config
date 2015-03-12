<?php

namespace PHPixie\Tests;

class ConfigStub extends \PHPixie\Config
{
    protected $builderMock;
    
    public function __construct($builderMock, $slice)
    {
        $this->builderMock = $builderMock;
        parent::__construct($slice);
    }
    
    protected function buildBuilder($slice)
    {
        return $this->builderMock;
    }
}

/**
 * @coversDefaultClass \PHPixie\Config
 */
class ConfigTest extends \PHPixie\Test\Testcase {
    protected $slice;

    protected $config;
    
    protected $builder;
    protected $storages;
    
    public function setUp()
    {
        $this->slice = $this->quickMock('\PHPixie\Slice');
        $this->builder = $this->quickMock('\PHPixie\Config\Builder');
        
        $this->config = new ConfigStub(
            $this->builder,
            $this->slice
        );
        
        $this->storages = $this->quickMock('\PHPixie\Config\Storages');
        
        $this->method($this->builder, 'storages', $this->storages, array());
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::file
     * @covers ::<protected>
     */
    public function testFile()
    {
        $fileStorage = $this->quickMock('\PHPixie\Config\Storages\Type\File');
        
        $file = 'pixie.txt';
        
        $this->method($this->storages, 'file', $fileStorage, array($file), 0);
        $this->assertSame($fileStorage, $this->config->file($file));
    }
    
    /**
     * @covers ::directory
     * @covers ::<protected>
     */
    public function testDirectory()
    {
        $driectoryStorage = $this->quickMock('\PHPixie\Config\Storages\Type\File');
        
        $dir = 'config';
        $name = 'pixie';
        $extension = 'json';
        
        $this->method($this->storages, 'directory', $driectoryStorage, array($dir, $name, $extension), 0);
        $this->assertSame($driectoryStorage, $this->config->directory($dir, $name, $extension));
        
        $this->method($this->storages, 'directory', $driectoryStorage, array($dir, $name, 'php'), 0);
        $this->assertSame($driectoryStorage, $this->config->directory($dir, $name));
    }
    
    /**
     * @covers ::builder
     * @covers ::<protected>
     */
    public function testBuilder()
    {
        $this->assertSame($this->builder, $this->config->builder());
    }
    
    /**
     * @covers ::buildBuilder
     * @covers ::<protected>
     */
    public function testBuilderInstance()
    {
        $this->config = new \PHPixie\Config(
            $this->slice
        );
        
        $builder = $this->config->builder();
        $this->assertInstance($builder, '\PHPixie\Config\Builder', array(
            'slice' => $this->slice,
        ));
    }
}