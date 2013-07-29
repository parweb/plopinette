<?php

include_once __DIR__.'/../core/class/helpers/file.php';
include_once __DIR__.'/../core/class/CoreModel.php';
include_once __DIR__.'/../app/module/allocine/class/AllocineModel.php';

$config = include __DIR__.'/../app/config/bdd.php';
$params = include __DIR__.'/../app/config/params.php';

$bdd = $config['bdd'][$params['site']['env']];

mysql_connect( $bdd['host'], $bdd['user'], $bdd['mdp'] );
mysql_select_db( $bdd['base'] );

$ids = array();

$query = mysql_query( "SELECT allocine_id FROM video ORDER BY allocine_id ASC" );
while ( $row = mysql_fetch_assoc( $query ) ) {
	$ids[] = $row['allocine_id'];
}

$step = 20;

$all = range( 50000, 300000 );

$diff = array_diff( $all, array_values( $ids ) );

$rand = array_slice( $diff, rand( 0, count( $diff ) - 1 ), $step );

foreach ( $rand as $ii => $i ) {
	$Allocine = new AllocineModel( $i );
	$Allocine->getTitle();

	echo "$i - $Allocine->allocine_titre\n";

	if ( !empty( $Allocine->allocine_titre ) && $Allocine->allocine_titre != '' ) {
		mysql_query( "INSERT INTO video ( `allocine_id`, `title`, `date`, `status` ) VALUES ( $Allocine->allocine_id, '".mysql_escape_string( $Allocine->allocine_titre )."', NOW(), 0 )" );
	}
}