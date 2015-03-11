<?php

namespace PHPixieTests\Config\Storages;

/**
 * @coversDefaultClass \PHPixie\Config\Storages\Data
 */
class DataTest extends \PHPixieTests\Config\StorageTest
{
    protected function getStorage($key = null)
    {
        return new \PHPixie\Config\Storages\Data($this->config, $this->data, $key);
    }

}
