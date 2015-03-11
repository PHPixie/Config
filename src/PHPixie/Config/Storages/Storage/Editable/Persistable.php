<?php

namespace PHPixie\Config\Storages\Storage\Editable;

interface Persistable extends \PHPixie\Config\Storages\Storage\Editable
{
    public function persist();
}
