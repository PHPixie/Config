<?php

namespace PHPixieTests\Config\Storage;

/**
 * @coversDefaultClass \PHPixie\Config\Storage\Persistable
 */
abstract class PersistableTest extends \PHPixieTests\Config\StorageTest
{

    protected $persistedData = array(
            'grass_type' => 8,
            'fairies' => array(
                'names' => array('Pixie')
            )
        );

    /**
     * @covers ::persist
     * @covers ::<protected>
     */
    public function testPersist()
    {
        $this->storage->set('meadow', $this->persistedData);
        $this->storage->persist();
    }
}
