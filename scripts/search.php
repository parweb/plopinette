<?php

include_once __DIR__.'/../core/class/helpers/file.php';
include_once __DIR__.'/../core/class/CoreModel.php';
include_once __DIR__.'/../app/module/allocine/class/AllocineModel.php';

setlocale(LC_ALL, 'fr_FR.UTF8');

$config = include __DIR__.'/../app/config/bdd.php';
$params = include __DIR__.'/../app/config/params.php';

$bdd = $config['bdd'][$params['site']['env']];

mysql_connect( $bdd['host'], $bdd['user'], $bdd['mdp'] );
mysql_select_db( $bdd['base'] );

$sql = "SELECT id, title, title_o FROM video WHERE search = ''";
$query = mysql_query( $sql );

while ( $item = mysql_fetch_assoc( $query ) ) {
	$search = $item['title'];

	if ( $item['title'] != $item['title_o'] ) {
		$search .= " $item[title_o]";
	}

	$search = clean( $search, array( "'" ) );

	mysql_query( "UPDATE video SET search = '$search' WHERE id = $item[id]" );
}

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