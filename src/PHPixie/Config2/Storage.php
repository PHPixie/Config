<?php

namespace PHPixie\Config;

abstract class Storage extends \PHPixie\Config\Slice
{
    public function slice($key = null)
    {
        return $this->config->buildSlice($this, $key);
    }
}
