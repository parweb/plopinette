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
	$sql = "SELECT id, allocine_id FROM video WHERE status = 1 AND image LIKE '%.gif' ORDER BY RAND() LIMIT 0, 300";
	$sql = "SELECT id, allocine_id FROM video WHERE status = 1 AND image LIKE '%.gif' ORDER BY RAND()";
	$sql = "SELECT id, allocine_id FROM video WHERE status = 1 AND image LIKE '%.gif' ORDER BY `release` DESC";
}

$req = mysql_query( $sql );

while ( $item = mysql_fetch_assoc( $req ) ) {
echo '<pre>'.__FILE__.' ( '.__LINE__.' ) ';
	print_r( $item );
echo '</pre>';exit;
	$allocine_id = $item['allocine_id'];
	$id = $item['id'];

	echo $id.' ';

	$AllocineModel = new AllocineModel( $allocine_id );
	$AllocineModel->getImagesBig();

	if ( !empty( $AllocineModel->allocine_imageBig ) && $AllocineModel->allocine_imageBig != 'http://images.allocine.fr/r_160_240/b_1_d6d6d6/commons/emptymedia/empty_photo.jpg' ) {
		$_img = $AllocineModel->allocine_imageBig;
	}
	else {
		$AllocineImgModel = new AllocineImgModel( $allocine_id );
		$AllocineImgModel->getImagesBig();

		$_img = $AllocineImgModel->allocine_imageBig;
	}

	$new_file_name = '';

	$url_img = $_img;

	if ( $img = file::get( $url_img ) ) {
		$extention = end( explode( '.', $url_img ) );

		$new_file_dir = __DIR__.'/../app/module/video/upload/';
		$new_file_name = strtolower( "$allocine_id.$extention" );
		$new_file = $new_file_dir.$new_file_name;

		if ( !@getimagesize( $new_file ) || !empty( $_GET['id'] ) ) {
			file::put( $new_file, $img );

			echo "ADD";
		}
		else {
			echo "NOTHING";
		}

		mysql_query( "UPDATE `video` SET `image` = '$new_file_name' WHERE `video`.`allocine_id` = $allocine_id" );
	}

	echo " - $Allocine->allocine_titre\n";
	//echo "\n";
}