<?php

include_once 'core/class/helpers/file.php';
include_once 'core/class/helpers/s3.php';
include_once 'core/class/CoreModel.php';
include_once 'app/module/allocine/class/AllocineModel.php';

$config = include 'app/config/bdd.php';

mysql_connect( $config['bdd']['host'], $config['bdd']['user'], $config['bdd']['mdp'] );
mysql_select_db( $config['bdd']['base'] );

$s3 = new s3( awsAccessKey, awsSecretKey );
$list = $s3->getBucket( bucket );

foreach ( $list as $item ) {
	$allocine_id = str_replace( '.'.end( explode( '.', $item['name'] ) ), '', $item['name'] );

	$existe = false;

	$sql = "SELECT id FROM video WHERE allocine_id = $allocine_id";
	$req = mysql_query( $sql );

	while ( $res = mysql_fetch_assoc( $req ) ) {
		$existe = $res;
	}

	if ( !(int)$existe['id'] ) {
		$Allocine = new AllocineModel( $allocine_id );
		$Allocine->getTitle();
		$Allocine->getImagesBig();

		echo $Allocine->allocine_titre;

		$new_file_name = '';

		if ( $img = file::get( $Allocine->allocine_imageBig ) ) {
			$extention = end( explode( '.', $Allocine->allocine_imageBig ) );

			$new_file_dir = '/home/parweb/dailymatons/app/module/video/upload/';
			$new_file_name = "$allocine_id.$extention";

			file::put( $new_file_dir.$new_file_name, $img );
		}

		mysql_query( "INSERT INTO video ( `allocine_id`, `title`, `status`, `date`, `image` ) VALUES ( $Allocine->allocine_id, '".mysql_escape_string( $Allocine->allocine_titre )."', 1, NOW(), '$new_file_name' )" );
		echo " - OK\n";
	}
}