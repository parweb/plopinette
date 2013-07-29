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
	$sql = "SELECT `video`.`id`, `video`.`allocine_id` FROM `video` WHERE `video`.`status` = 1 AND `video`.`release` = '0000-00-00' ORDER BY RAND() LIMIT 0, 300";
}

$req = mysql_query( $sql );

while ( $item = mysql_fetch_assoc( $req ) ) {
	$allocine_id = $item['allocine_id'];
	$id = $item['id'];

	echo $id;

	$AllocineImgModel = new AllocineImgModel( $allocine_id );
	$AllocineImgModel->getDateSortie();

	$AllocineModel = new AllocineModel( $allocine_id );
	$AllocineModel->getDateSortie();

	$date_release = $AllocineImgModel->allocine_dateSortie;
	if ( $AllocineModel->allocine_dateSortie != '0000-00-00' ) {
		$date_release = $AllocineModel->allocine_dateSortie;
	}

	mysql_query( "UPDATE `video` SET `release` = '$date_release' WHERE `video`.`allocine_id` = $allocine_id" );

	echo "\n";
}