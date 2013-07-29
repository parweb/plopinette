<?php

require_once 'Singleton.php';

class Config extends Singleton {
	public static $configs = array();

	static public function init () {
		$sub_root = str_replace( '//', '/', dirname( $_SERVER['SCRIPT_NAME'] ).'/' );
		$uri = trim( str_replace( $sub_root, '/', $_SERVER['REQUEST_URI'] ), '/' );

		$root = $_SERVER['DOCUMENT_ROOT'];
		if ( $sub_root ) {
			$root .= $sub_root;
		}

		define( 'HOST', $_SERVER['HTTP_HOST'] );
		define( 'url', 'http://'.HOST.$sub_root );

		// dossier principaux de beer
		define( 'DIR', $root );
		define( 'URL', $sub_root );
			define( 'APP', 'app'.DS );
				define( 'SQL', 'sql'.DS );
				define( 'CONFIG', 'config'.DS );
				define( 'TMP', 'tmp'.DS );
					define( 'CACHE', TMP.'cache'.DS );
				define( 'TEMPLATE', 'template'.DS );
					define( 'CSS', 'css'.DS );
					define( 'JS', 'js'.DS );
				define( 'MAIL', 'mail'.DS );
				define( 'LOGS', 'logs'.DS );
				define( 'MODULE', 'module'.DS );
					define( 'MODULE_CLASS', MODULE.'class'.DS );
			define( 'CORE', 'core'.DS );
				define( 'CORE_CLASS', CORE.'class'.DS );
				define( 'FIREWALL', 'php-firewall'.DS );

		define( 'URI', $uri );
	}

	static public function loadFiles () {
		if ( $files = glob ( DIR.APP.CONFIG.'*.php' ) ) {
			array_map( 'config::load', $files );
		}
	}

	static public function load ( $file ) {
		if ( file_exists( $file ) ) {
			$filename = $file;
		}
		else {
			$filename = DIR.APP.CONFIG."$file.php";

			if ( !file_exists( $filename ) ) {
				throw new Exception( "Config file '$file' not found" );
			}
		}

		$config = include_once $filename;

		foreach ( $config as $section => $value ) {
			self::$configs[$section] = $value;
		}
	}
}