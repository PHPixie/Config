<?php

namespace PHPixie;

/**
 * Handles retrieving of the configuration options.
 * You can add configuration files to /assets/config folder
 * and later access them via the get() method.
 * @package Core
 */
abstract class Config {
	

	
	protected abstract function full_key($key);
	
	protected abstract function find($key);
	
}
