<?php

namespace PHPixieTests;

/**
 * @coversDefaultClass \PHPixie\Config
 */
class ConfigTest extends AbstractConfigTest{
    
    protected $config;
    
    public function setUp() {
        $this->config = new \PHPixie\Config;
    }
    
    /**
     * @covers ::dataStorage
     */
    public function testDataStorage(){
        $storage = $this->config->dataStorage(array('test' => 5), 'pixie');
        $this->assertInstanceOf('\PHPixie\Config\Storages\Data', $storage);
        $this->assertEquals('pixie', $storage-> key());
        $this->assertAttributeEquals(array('test' => 5), 'data' ,$storage);
    }
    
    /**
     * @covers ::fileStorage
     */
    public function testFileStorage(){
        $storage = $this->config->fileStorage('test.php', 'pixie');
        $this->assertInstanceOf('\PHPixie\Config\Storages\File', $storage);
        $this->assertEquals('pixie', $storage->key());
        $this->assertAttributeEquals('test.php', 'file' ,$storage);
    }
    
    /**
     * @covers ::directoryStorage
     */
    public function testDirectoryStorage(){
        $storage = $this->config->directoryStorage('test', 'forest', 'php', 'pixie');
        $this->assertInstanceOf('\PHPixie\Config\Storages\Directory', $storage);
        $this->assertEquals('pixie', $storage->key());
        $this->assertAttributeEquals('test', 'directory' ,$storage);
        $this->assertAttributeEquals('forest', 'name' ,$storage);
        $this->assertAttributeEquals('php', 'extension' ,$storage);
    }
    
    /**
     * @covers ::buildSlice
     * @covers ::<protected>
     */
    public function testBuildSlice(){
        $slice = $this->config->buildSlice('test', 'pixie');
        $this->assertInstanceOf('\PHPixie\Config\Storage\Slice', $slice);
        $this->assertEquals('pixie', $slice->key());
        $this->assertAttributeEquals('test', 'storage', $slice);
    }
    
    /**
     * @covers ::fileHandlers
     * @covers ::<protected>
     */
    public function testFileHandlers(){
        $handlers = $this->config->fileHandlers();
        $this->assertInstanceOf('\PHPixie\Config\Storages\File\Handlers', $handlers);
        $this->assertEquals($handlers, $this->config->fileHandlers());
    }
}