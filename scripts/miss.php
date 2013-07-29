<?php

$config = include __DIR__.'/../app/config/bdd.php';
$params = include __DIR__.'/../app/config/params.php';

$bdd = $config['bdd'][$params['site']['env']];

mysql_connect( $bdd['host'], $bdd['user'], $bdd['mdp'] );
mysql_select_db( $bdd['base'] );

$dir_films = '/home/downloads/ok/';

// désactive tous les films
mysql_query( "UPDATE video SET status = 0" );

$films = glob( "$dir_films*.*" );
$ids = array();

foreach ( $films as $film ) {
	$name = basename( $film );

	$ids[] = str_replace( '.'.end( explode( '.', $name ) ), '', $name );
}

// Active les films disponibles
mysql_query( "UPDATE video SET status = 1 WHERE allocine_id IN ( ".join( ', ', $ids )." )" );

// MAJ fields view dans la table video en fonction de buy
$sql = "SELECT video_id, count FROM famous_videos";
$req = mysql_query( $sql );

while ( $item = mysql_fetch_assoc( $req ) ) {
	mysql_query( "UPDATE video SET view = $item[count] WHERE id = $item[video_id]" );
}