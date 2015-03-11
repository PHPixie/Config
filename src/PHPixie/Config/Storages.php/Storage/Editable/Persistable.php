<?php

namespace PHPixie\Config\Storages\Storage\Editable;

interface Persistable extends \PHPixie\Config\Storages\Editable
{
    public function persist();
}
