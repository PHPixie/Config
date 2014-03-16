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

    /**
     * @covers ::get
     * @covers ::<protected>
     */
    public function testGet()
    {
        $this->assertEquals($this->data, $this->storage->get());
        $this->assertEquals(5, $this->storage->get('meadows'));
        $this->assertEquals(6, $this->storage-> get('meadow.grass_type'));
        $this->assertEquals(array('names' => array('Naiad')), $this->storage-> get('lake.mermaids'));
        $this->assertEquals(array(
            'grass_type' => 6,
            'fairies' => array(
                'names' => array('Tinkerbell')
            ),
            'trees' => array(
                'oak' => array(
                    'fairy' => array('Trixie')
                )
            )
        ), $this->storage-> get('meadow'));
        $this->assertEquals('Trixie', $this->storage-> get('meadow.trees.oak.fairy.0'));

        $this->assertEquals('test', $this->storage-> get('meadow.grass_type.pixies', 'test'));
        $this->assertException(function () {
            $this->storage-> get('meadow.grass_type.pixies');
        });
        $this->assertException(function () {
            $this->storage-> get('meadow.grass_type.pixies.name');
        });

        $this->storage->remove(null);

        $this->assertEquals('test', $this->storage->get(null, 'test'));
        $this->assertException(function () {
            $this->assertEquals('test', $this->storage-> get(null));
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

    protected function assertException($callback)
    {
        $except = false;
        try {
            $callback();
        } catch (\PHPixie\Config\Exception $e) {
            $except = true;
        }
        $this->assertEquals(true, $except);
    }

    abstract protected function getStorage($key = null);
}
