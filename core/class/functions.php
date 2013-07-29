<?php

// function d'autoload ( chargement automatique des classes )
function __autoload ( $class ) {
	if ( preg_match( '|^Core|', $class ) ) {
		//echo('commence par Core: '.$class.'<br />');

		$class_file = DIR.CORE_CLASS.$class.'.php';
	}
	else if ( preg_match( '|^App|', $class ) ) {
		//echo('commence par App: '.$class.'<br />');

		$class_file = DIR.APP.MODULE.$class.'.php';
	}
	else if ( $class == 'Beer' ) {
		//echo('commence par App: '.$class.'<br />');

		$class_file = DIR.CORE_CLASS.$class.'.php';
	}
	else if ( $class == 'Facebook' ) {
		//echo('commence par App: '.$class.'<br />');

		$class_file = DIR.CORE.'facebook'.DS.strtolower( $class ).'.php';
	}
	else if ( $class[0] == strtolower( $class[0] ) || $class[0] == '_' ) {
		//echo('commence par une minuscule: '.$class.'<br />');

		// regarde si un module nexiste pas avec ce helper
		$helper_module = DIR.APP.MODULE.$class.DS.'helpers'.DS.$class.'.php';;
		if ( file_exists( $helper_module ) ) {
			//echo('module helpers : '.$class.'<br />');

			$class_file = $helper_module;
		}
		else {
			//echo('core helpers : '.$class.'<br />');

			$class_file = DIR.CORE_CLASS.'helpers'.DS.$class.'.php';
		}
	}
	else {
		if ( preg_match( '|_|', $class ) ) {
			exit('module contient au moins 1 _: '.$class.'<br />');
		}
		else {
			if ( preg_match( '|Model$|', $class ) ) {
				//echo('module Model : '.$class.'<br />');

				$module_name = strtolower( str_replace( 'Model', '', $class ) );
				$class_name = 'class'.DS.$class;
			}
			else if ( preg_match( '|Controler$|', $class ) ) {
				//echo('module Controler : '.$class.'<br />');

				$module_name = strtolower( str_replace( 'Controler', '', $class ) );
				$class_name = $class;
			}
		}

		$class_file = DIR.APP.MODULE.$module_name.DS.$class_name.'.php';
	}

	//echo $class_file.'<br />';
	//debug('file_exists: '.$class_file);

	if ( file_exists( $class_file ) ) {
		//debug('file_exists: '.$class_file);
		//echo "<u>\$class_file</u>: <b>".$class_file.'</b><br />';
		require_once $class_file;
	}
	else {
		header( 'location: /' );
		debug('La classe <b>'.$class.'</b> n\'existe pas '.$class_file, false, true);
		$class_file = DIR.CORE_CLASS.$class.'.php';

		require_once $class_file;
	}
}

// si gettext n'est pas activé
if ( !function_exists('_') ) {
	function _ ( $string ) { return $string; }
}

function param ( $key, $default = null ) {
	$URL = url::url2params();

	return isset( $URL[$key] ) ? $URL[$key] : $default;
}

function url ( $key, $default = null ) {
	return param( $key, $default );
}

function config ( $path, $default = null ) {
	$config = eval( 'return Config::$configs[\''.join( "']['", explode( '.', $path ) ).'\'];' );

	return isset( $config ) ? $config : $default;
}

function debug ( $data, $exit = false, $debug = false ) {
	$infos = current( debug_backtrace() );

	echo '<pre>'.$infos['file'].' ( '.$infos['line'].' ) ';
		print_r( $data );
	echo '</pre>';

	if ( $debug ) {
		debug( current( debug_backtrace() ) );
	}

	if ( $exit ) {
		exit;
	}
}

setlocale( LC_ALL, 'fr_FR.UTF8' );
function clean ( $str, $replace = array(), $delimiter = ' ' ) {
	if ( !empty( $replace ) ) {
		$str = str_replace( (array)$replace, ' ', $str );
	}

	$clean = iconv( 'UTF-8', 'ASCII//TRANSLIT', $str );
	$clean = preg_replace( "/[^a-zA-Z0-9\/_|+ -]/", '', $clean );
	$clean = strtolower( trim( $clean, '-' ) );
	$clean = preg_replace( "/[\/_|+ -]+/", $delimiter, $clean );

	return $clean;
}