<?php

namespace PHPixieTests\Config\Storages;

/**
 * @coversDefaultClass \PHPixie\Config\Storages\File
 */
class FileTest extends \PHPixieTests\Config\Storage\PersistableTest
{
    protected $handler;
    protected $storage;
    protected $dataStorage;
    protected $file;

    public function setUp()
    {
        $this->file = sys_get_temp_dir().'/phpixie_config_file.php';
        $this->removeFile();
        file_put_contents($this->file, "");

        $this->dataStorage = $this->getMock('\PHPixie\Config\Storages\Data', array(), array(), '', false);
        $this->handler = $this->getMock('\PHPixie\Config\Storages\File\Handler', array('read', 'write'));
        parent::setUp();
    }

    public function tearDown()
    {
        $this->removeFile();
    }

    protected function prepareGetDataSets()
    {
        $this->assertRead();
        
        $sets = array();
        
        $this->dataStorage
                    ->expects($this->at(2))
                    ->method('getData')
                    ->with('pixie', false, null)
                    ->will($this->returnValue('test'));
        var_dump($this->storage->getData('pixie', false, null));die;
        $sets[]= array('get', array('pixie'), 'test');
        /*
        $this->dataStorage
                    ->expects($this->at(2))
                    ->method('getData')
                    ->with('pixie', false, 5)
                    ->will($this->returnValue('test'));
        
        $sets[]= array('get', array('pixie', 5), 'test');
        
        $this->dataStorage
                    ->expects($this->at(3))
                    ->method('getData')
                    ->with('pixie', true, null)
                    ->will($this->returnValue('test'));
        
        $sets[]= array('getRequired', array('pixie'), 'test');
        
        $this->dataStorage
                    ->expects($this->at(4))
                    ->method('getData')
                    ->with('pixie', true, null)
                    ->will($this->throwException(new \PHPixie\Config\Exception()));
        
        $sets[]= array('getRequired', array('pixie'), 'exception');
        */
        return $sets;
    }

    /**
     * @covers ::get
     * @covers ::<protected>
     */
    public function testReadEmpty()
    {
        unlink($this->file);
        $this->handler
            ->expects($this->never())
            ->method('read');

        $this->dataStorage
                        ->expects($this->at(0))
                        ->method('set')
                        ->with (null, array());
        $this->dataStorage
                        ->expects($this->at(1))
                        ->method('get')
                        ->with ('test', 'test2')
                        ->will($this->returnValue(5));
        $this->assertEquals(5, $this->storage->get('test', 'test2'));
    }

    /**
     * @covers ::set
     * @covers ::<protected>
     */
    public function testSet()
    {
        $this->assertRead();
        $this->dataStorage
                        ->expects($this->at(1))
                        ->method('set')
                        ->with ('test', 5);
        $this->assertAttributeEquals(false, 'modified', $this->storage);
        $this->storage->set('test', 5);
        $this->assertAttributeEquals(true, 'modified', $this->storage);
    }

    /**
     * @covers ::remove
     * @covers ::<protected>
     */
    public function testRemove()
    {
        $this->assertRead();
        $this->dataStorage
                        ->expects($this->at(1))
                        ->method('remove')
                        ->with ('test');
        $this->assertAttributeEquals(false, 'modified', $this->storage);
        $this->storage->remove('test');
        $this->assertAttributeEquals(true, 'modified', $this->storage);
    }
    
    /**
     * @covers ::keys
     * @covers ::<protected>
     */
    public function testKeys()
    {
        $this->assertRead();
        
        $this->dataStorage
                        ->expects($this->at(1))
                        ->method('keys')
                        ->with ('test', false);
        
        $this->dataStorage
                        ->expects($this->at(1))
                        ->method('keys')
                        ->with ('test', true);
        
        $this->assertAttributeEquals(false, 'modified', $this->storage);
        
        $this->storage->remove('test');
        $this->storage->remove('test', true);
        
        $this->assertAttributeEquals(false, 'modified', $this->storage);
    }

    /**
     * @covers ::persist
     * @covers ::<protected>
     */
    public function testPersistNotModified()
    {
        $this->handler
                        ->expects($this->never())
                        ->method('write');
        $this->storage-> persist();
        $this->assertAttributeEquals(false, 'modified', $this->storage);
    }

    /**
     * @covers ::persist
     * @covers ::<protected>
     */
    public function testPersistModified()
    {
        $this->storage->set('test', 5);
        $this->dataStorage
                ->expects($this->once())
                ->method('get')
                ->with (null, null)
                ->will($this->returnValue(array(
                    'test' => 5
                )));
        $this->handler
                ->expects($this->once())
                ->method('write')
                ->with ($this->file, array('test' => 5));

        $this->storage-> persist();
        $this->assertAttributeEquals(false, 'modified', $this->storage);
    }

    /**
     * @covers ::persist
     * @covers ::<protected>
     */
    public function testPersistUnlink()
    {
        $this->storage->set('test', 5);
        $this->dataStorage
                ->expects($this->once())
                ->method('get')
                ->with (null, null)
                ->will($this->returnValue(array()));

        $this->storage-> persist();
        $this->assertAttributeEquals(false, 'modified', $this->storage);
        $this->assertEquals(false, file_exists($this->file));
    }

    protected function getStorage($key = null)
    {
        return new \PHPixie\Config\Storages\File($this->config, $this->dataStorage, $this->handler, $this->file, $key);
    }

    protected function assertRead()
    {
        $this->handler
            ->expects($this->once())
            ->method('read')
            ->will($this->returnValue($this->data));
    }

    protected function removeFile()
    {
        if(file_exists($this->file))
            unlink($this->file);
    }
}
