<?php

namespace PHPixie\Tests\Config\Storages\Type;

/**
 * @coversDefaultClass \PHPixie\Config\Storages\Type\File
 */
class FileTest extends \PHPixie\Tests\Slice\Data\ImplementationTest
{
    protected $formats;
    protected $file;
    protected $parameters;
    
    protected $format;
    protected $arrayData;

    public function setUp()
    {
        $this->formats = $this->quickMock('\PHPixie\Config\Formats');
        $this->file = sys_get_temp_dir().'/phpixie_config_file.php';
        $this->removeFile();
        
        $this->parameters = $this->quickMock('\PHPixie\Slice\Data');
        
        $this->arrayData = $this->quickMock('\PHPixie\Slice\Type\ArrayData\Editable');
        $this->format   = $this->quickMock('\PHPixie\Config\Formats\Format');
        parent::setUp();
    }

    public function tearDown()
    {
        $this->removeFile();
    }

    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
        
    /**
     * @covers ::keys
     * @covers ::<protected>
     */
    public function testKeys()
    {
        $this->prepareArrayData();
        
        $keys = array('test');

        $this->method($this->arrayData, 'keys', $keys, array('names', false), 0);
        $this->assertSame($keys, $this->sliceData->keys('names'));
        
        $this->method($this->arrayData, 'keys', $keys, array('names', true), 0);
        $this->assertSame($keys, $this->sliceData->keys('names', true));
        
        $this->method($this->arrayData, 'keys', $keys, array(null, false), 0);
        $this->assertSame($keys, $this->sliceData->keys());
    }
    
    /**
     * @covers ::keys
     * @covers ::<protected>
     */
    public function testReadEmpty()
    {
        $this->prepareArrayData(false);
        
        $keys = array();

        $this->method($this->arrayData, 'keys', $keys, array('names', false), 0);
        $this->assertSame($keys, $this->sliceData->keys('names'));
    }
    
    /**
     * @covers ::slice
     * @covers ::<protected>
     */
    public function testSlice()
    {
        $this->prepareArrayData();
        
        $slice = $this->getSlice();
        
        $this->method($this->arrayData, 'slice', $slice, array('names'), 0);
        $this->assertSame($slice, $this->sliceData->slice('names'));
        
        $this->method($this->arrayData, 'slice', $slice, array(null), 0);
        $this->assertSame($slice, $this->sliceData->slice());
    }
    
    /**
     * @covers ::arraySlice
     * @covers ::<protected>
     */
    public function testArraySlice()
    {
        $this->prepareArrayData();
        
        $slice = $this->getArraySlice();
        
        $this->method($this->arrayData, 'arraySlice', $slice, array('names'), 0);
        $this->assertSame($slice, $this->sliceData->arraySlice('names'));
        
        $this->method($this->arrayData, 'arraySlice', $slice, array(null), 0);
        $this->assertSame($slice, $this->sliceData->arraySlice());
    }
    
    /**
     * @covers ::getIterator
     * @covers ::<protected>
     */
    public function testIterator()
    {
        $this->prepareArrayData();
        
        $iterator = $this->quickMock('\Iterator');
        
        $this->method($this->arrayData, 'getIterator', $iterator, array(), 0);
        $this->assertSame($iterator, $this->sliceData->getIterator());
    }
    
    /**
     * @covers ::set
     * @covers ::<protected>
     */
    public function testSet()
    {
        $this->prepareArrayData();
        
        $this->method($this->arrayData, 'set', null, array('names', 5), 0);
        $this->sliceData->set('names', 5);
        
        $this->method($this->arrayData, 'set', null, array(null, 5), 0);
        $this->sliceData->set(null, 5);
    }
    
    /**
     * @covers ::remove
     * @covers ::<protected>
     */
    public function testRemove()
    {
        $this->prepareArrayData();
        
        $this->method($this->arrayData, 'remove', null, array('names'), 0);
        $this->sliceData->remove('names');
        
        $this->method($this->arrayData, 'remove', null, array(), 0);
        $this->sliceData->remove();
    }
    
    /**
     * @covers ::get
     * @covers ::<protected>
     */
    public function testParameters()
    {
        $this->prepareArrayData();
        
        $this->method($this->arrayData, 'getData', '%pixie%', array('name', false, null), 0);
        $this->method($this->parameters, 'getRequired', 5, array('pixie'), 0);
        $this->assertSame(5, $this->sliceData->get('name'));
        
        $this->method($this->arrayData, 'getData', array('test' => '%pixie%'), array('name', false, null), 0);
        $this->method($this->parameters, 'getRequired', 5, array('pixie'), 0);
        $this->assertSame(array('test' => 5), $this->sliceData->get('name'));
    }
    
    /**
     * @covers ::persist
     * @covers ::<protected>
     */
    public function testPersistNotModified()
    {
        $this->assertPersistNotModified();
    }
    
    /**
     * @covers ::persist
     * @covers ::<protected>
     */
    public function testPersistModified()
    {
        $this->prepareArrayData();
        $data = array('test' => 5);
        
        $this->sliceData->set('test', 5);
        $this->method($this->arrayData, 'getData', $data, array(null, false, array()), 0);
        $this->method($this->format, 'write', null, array($this->file, $data), 0);

        $this->sliceData->persist();
        $this->assertEquals(true, file_exists($this->file));
        
        $this->sliceData->set('test', 5);
        $this->method($this->arrayData, 'getData', array(), array(null, false, array()), 0);
        $this->method($this->format, 'write', null, array($this->file, array()), 0);
        
        $this->sliceData->persist();
        $this->assertEquals(true, file_exists($this->file));
        
        $this->assertPersistNotModified();
    }

    /**
     * @covers ::persist
     * @covers ::<protected>
     */
    public function testPersistUnlink()
    {
        $this->prepareArrayData();
        $data = array('test' => 5);
        
        $this->sliceData->set('test', 5);
        $this->method($this->arrayData, 'getData', $data, array(null, false, array()), 0);
        $this->method($this->format, 'write', null, array($this->file, $data), 0);

        $this->sliceData->persist(true);
        $this->assertEquals(true, file_exists($this->file));
        
        $this->sliceData->set('test', 5);
        $this->method($this->arrayData, 'getData', array(), array(null, false, array()), 0);
        
        $this->sliceData->persist(true);
        $this->assertEquals(false, file_exists($this->file));
        
        $this->assertPersistNotModified();
    }
    
    protected function assertPersistNotModified()
    {
        $this->format
                     ->expects($this->never())
                     ->method('write');
        $this->sliceData->persist();
    }
    
    protected function prepareGetDataSets()
    {
        $this->prepareArrayData();
        $sets = array();
        
        $this->method($this->arrayData, 'getData', 5, array('name', false, null), 0);
        $sets[] = array('get', array('name'), 5);
        
        $this->method($this->arrayData, 'getData', 5, array('name', true, null), 1);
        $sets[] = array('getRequired', array('name'), 5);
        
        $this->method($this->arrayData, 'getData', 5, array('name', true, 4), 2);
        $sets[] = array('getData', array('name', true, 4), 5);
        
        $this->method($this->arrayData, 'getData', 5, array(null, false, null), 3);
        $sets[] = array('get', array(), 5);
        
        $this->method($this->arrayData, 'getData', 5, array(null, true, null), 4);
        $sets[] = array('getRequired', array(), 5);
        
        $this->method($this->arrayData, 'getData', 5, array(null, true, 4), 5);
        $sets[] = array('getData', array(null, true, 4), 5);
        
        $this->method($this->arrayData, 'getData', 5, array('name', false, 4), 6);
        $sets[] = array('get', array('name', 4), 5);
        
        return $sets;
    }
    
    protected function prepareArrayData($existing = true)
    {
        if($existing) {
            $data = array('test');
            file_put_contents($this->file, '');
            $this->method($this->formats, 'getByFilename', $this->format, array($this->file), 0);
            $this->method($this->format, 'read', $data, array($this->file), 0);
        }else{
            $data = array();
        }
        
        $this->method($this->sliceBuilder, 'editableArrayData', $this->arrayData, array($data), 0);
    }
    
    protected function removeFile()
    {
        if(file_exists($this->file)) {
            unlink($this->file);
        }
    }
    
    protected function sliceData()
    {
        return new \PHPixie\Config\Storages\Type\File(
            $this->sliceBuilder,
            $this->formats,
            $this->file,
            $this->parameters
        );
    }
}
