<?php

abstract class Singleton {
	private static $instances = array ();


	public static function GetInstance () {
		$class = get_called_class ();

		if (! isset( self::$instances[$class] ) ) {
			self::$instances[$class] = new $class ();
		}

		return self::$instances [$class];
	}

	private function __construct () {}

	final private function __clone () {}

	final public function __sleep () {
		throw new Exception( 'Singleton classes can not be serialized' );
	}

	final public function __wakeup () {
		throw new Exception( 'Singleton classes can not be unserialized' );
	}
}
