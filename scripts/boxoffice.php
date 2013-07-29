<?php

include_once __DIR__.'/../core/class/helpers/file.php';
include_once __DIR__.'/../core/class/CoreModel.php';
include_once __DIR__.'/../app/module/allocine/class/AllocineModel.php';

$config = include __DIR__.'/../app/config/bdd.php';
$params = include __DIR__.'/../app/config/params.php';

$bdd = $config['bdd'][$params['site']['env']];

mysql_connect( $bdd['host'], $bdd['user'], $bdd['mdp'] );
mysql_select_db( $bdd['base'] );

if ( isset( $_GET['id'] ) && !empty( $_GET['id'] ) ) {
	$sql = "SELECT id, allocine_id FROM video WHERE allocine_id = $_GET[id]";
}
else {
	$sql = "SELECT id, allocine_id FROM video WHERE status = 1 ORDER BY RAND() LIMIT 0, 60";
	//$sql = "SELECT id, allocine_id FROM video WHERE status = 1 ORDER BY RAND()";
}

$req = mysql_query( $sql );

while ( $item = mysql_fetch_assoc( $req ) ) {
	$allocine_id = $item['allocine_id'];
	$id = $item['id'];

	$Allocine = new AllocineModel( $allocine_id );
	$Allocine->getBoxOffice();

	if ( !$Allocine->allocine_boxOffice ) {
		$Allocine->allocine_boxOffice = -1;
	}

	mysql_query( "UPDATE `video` SET `boxoffice` = '$Allocine->allocine_boxOffice' WHERE `id` = $id" );
}