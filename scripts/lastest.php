<?php

$config = include __DIR__.'/../app/config/bdd.php';
$params = include __DIR__.'/../app/config/params.php';

$bdd = $config['bdd'][$params['site']['env']];

mysql_connect( $bdd['host'], $bdd['user'], $bdd['mdp'] );
mysql_select_db( $bdd['base'] );

$dir_films = '/home/downloads/ok/';

$films = glob( "$dir_films*.*" );

foreach ( $films as $film ) {
	$name = basename( $film );

	$id = str_replace( '.'.end( explode( '.', $name ) ), '', $name );

	mysql_query( "UPDATE video SET date = '".date( 'Y-m-d H:i:s', filemtime( $film ) )."' WHERE id = $id" );
}