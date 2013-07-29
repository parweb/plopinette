<?php

class asset extends Singleton {
	public static $assets = array();
	public static $type;

	static public function add ( $type, $datas ) {
		if ( isset( self::$assets[$type] ) ) {
			self::$assets[$type] = array_merge( self::$assets[$type], $datas );
		}
		else {
			self::$assets[$type] = $datas;
		}
	}

	static public function get ( $type ) {
		return self::$assets[$type];
	}

	public static function minify ( $new_file = 'minify', $type = null ) {
		$new_file = "{$new_file}_".md5( json_encode( self::$assets[$type] ) );
		$path_asset = APP.TEMPLATE.config('view.template').DS.$type.DS;
		$path_cache = APP.TMP.'assets'.DS;

		$return = '';
		foreach ( self::$assets[$type] as $item ) {
			$return .= file::get( DIR.$path_asset.$item.'.'.$type );
		}

		// Strips Comments
		$return = preg_replace( '!/\*.*?\*/!s', '', $return );
		$return = preg_replace( '/\n\s*\n/', "\n", $return );

		// Minifies
		if ( $type == 'css' ) $return = preg_replace( '/[\n\r]/', ' ', $return );
		$return = preg_replace( '/[\t]/', '', $return );
		$return = preg_replace( '/ +/', ' ', $return );

		// return
		$return = preg_replace( '/ ?([,:;{}]) ?/', '$1', $return );

		if ( !file_exists( DIR.$path_cache.$new_file.'.'.$type ) ) file::put( DIR.$path_cache.$new_file.'.'.$type, $return );

		return URL."$path_cache$new_file.$type";
	}
}