<?php

namespace PHPixieTests\Config;

/**
 * @coversDefaultClass \PHPixie\Config\Storage
 */
abstract class StorageTest extends \PHPixieTests\Config\SliceTest
{

    protected $storage;
    protected $data = array(
        'meadows' => 5,
        'meadow' => array(
            'grass_type' => 6,
            'fairies' => array(
                'names' => array('Tinkerbell')
            ),
            'trees' => array(
                'oak' => array(
                    'fairy' => array('Trixie')
                )
            )
        ),
        'lake' => array(
            'mermaids' => array(
                            'names' => array('Naiad')
                        )
        )
    );

    public function setUp()
    {
        parent::setUp();
        $this->storage = $this->getStorage();
    }

    protected function prepareGetDataSets()
    {
        $sets = array();
        
        $sets[] = array('get', array(), $this->data);
        $sets[] = array('get', array('meadows'), 5);
        $sets[] = array('getRequired', array('meadow.grass_type'), 6);
        
        $sets[] = array('getRequired', array('lake.mermaids'), array('names' => array('Naiad')));
        $sets[] = array('get', array('meadow'), array(
            'grass_type' => 6,
            'fairies' => array(
                'names' => array('Tinkerbell')
            ),
            'trees' => array(
                'oak' => array(
                    'fairy' => array('Trixie')
                )
            )
        ));
        
        $sets[] = array('get', array('meadow.trees.oak.fairy.0'), 'Trixie');
        
        $sets[] = array('get', array('meadow.grass_type.pixies', 'test'), 'test');
        $sets[] = array('getRequired', array('meadow.grass_type.pixies'), 'exception');
        $sets[] = array('getRequired', array('meadow.grass_type.pixies.name'), 'exception');
        
        return $sets;
    }

    /**
     * @covers ::getData
     * @covers ::<protected>
     */
    public function testGetAfterRemove()
    {
        $this->storage->remove(null);

        $this->assertEquals('test', $this->storage->get(null, 'test'));
        $this->assertException(function () {
            $this->assertEquals('test', $this->storage->getRequired(null));
        });
    }
    
    /**
     * @covers ::set
     * @covers ::<protected>
     */
    public function testSet()
    {
        $this->storage->set('meadow.grass_type', 8);
        $this->assertEquals(8, $this->storage-> get('meadow.grass_type'));
        $this->storage->set('meadow.trail.length', 8);
        $this->assertEquals(8, $this->storage-> get('meadow.trail.length'));
        $this->assertException(function () {
            $this->storage-> set('meadow.grass_type.pixies', 8);
        });

        $this->storage->set('meadow', array(
            'grass_type' => 6,
            'fairies' => array(
                'names' => array('Pixie')
            )
        ));
        $this->assertEquals('Pixie', $this->storage->get('meadow.fairies.names.0'));
        $this->assertEquals(1, $this->storage-> get('meadow.trees.oak', 1));
        $this->storage-> set(null, array('test' => 5));
        $this->assertEquals('5', $this->storage-> get('test'));
        $this->assertException(function () {
            $this->storage-> set(null, 5);
        });
    }

    /**
     * @covers ::remove
     * @covers ::<protected>
     */
    public function testRemove()
    {
        $this->storage->remove('meadow.grass_type');
        $this->assertEquals('test', $this->storage-> get('meadow.grass_type', 'test'));
        $this->storage->remove('meadow.fairies');
        $this->assertEquals('test', $this->storage-> get('meadow.fairies,names', 'test'));
        $this->storage->remove(null);
        $this->assertEquals('test', $this->storage-> get('meadow', 'test'));
        $this->assertEquals('test', $this->storage-> get(null, 'test'));
    }

    /**
     * @covers ::slice
     * @covers ::<protected>
     */
    public function testSlice()
    {
        $this->config
                    ->expects($this->once())
                    ->method('buildSlice')
                    ->with ($this->storage, 'test');
        $this->storage->slice('test');
    }

    protected function getSlice($key = null)
    {
        return $this->getStorage($key);
    }
    
    protected function slice()
    {
        return $this->storage;
    }

    abstract protected function getStorage($key = null);
}
