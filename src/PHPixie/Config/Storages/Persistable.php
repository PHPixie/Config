<?php

namespace PHPixie\Config;

interface Persistable extends Storage
{
    public function persist();
}