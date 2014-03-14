<?php

namespace PHPixie\Config;

interface Slice
{
    public function get($key);
    public function slice($key);
    public function set($key, $value);
}