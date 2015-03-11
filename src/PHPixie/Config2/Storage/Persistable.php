<?php

namespace PHPixie\Config\Storage;

abstract class Persistable extends \PHPixie\Config\Storage
{
    abstract public function persist();
}
