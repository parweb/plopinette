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
	$sql = "SELECT id, allocine_id FROM video WHERE status = 1 AND abstract = '' ORDER BY RAND() LIMIT 0, 300";
	$sql = "SELECT id, allocine_id FROM video WHERE status = 1 AND abstract = '' ORDER BY RAND()";
}

echo "$sql\n\n";

$req = mysql_query( $sql );

while ( $item = mysql_fetch_assoc( $req ) ) {
	$allocine_id = $item['allocine_id'];
	$id = $item['id'];

	echo $id.' ';

	$AllocineModel = new AllocineModel( $allocine_id );
	$AllocineModel->getSynopsis();

	$AllocineImgModel = new AllocineImgModel( $allocine_id );
	$AllocineImgModel->getSynopsis();

	$new_synopsis = $AllocineModel->allocine_synopsis;

	if ( empty( $AllocineModel->allocine_synopsis ) ) {
		$new_synopsis = $AllocineImgModel->allocine_synopsis;
	}

	if ( !empty( $new_synopsis ) ) {
		mysql_query( "UPDATE `video` SET `abstract` = '$new_synopsis' WHERE `video`.`allocine_id` = $allocine_id" );
	}

	echo "\n";
}