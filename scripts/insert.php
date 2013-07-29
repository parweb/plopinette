<?php

include_once __DIR__.'/../core/class/helpers/file.php';
include_once __DIR__.'/../core/class/CoreModel.php';
include_once __DIR__.'/../app/module/allocine/class/AllocineModel.php';

$config = include __DIR__.'/../app/config/bdd.php';
$params = include __DIR__.'/../app/config/params.php';

$bdd = $config['bdd'][$params['site']['env']];

mysql_connect( $bdd['host'], $bdd['user'], $bdd['mdp'] );
mysql_select_db( $bdd['base'] );

$films = glob( '/home/downloads/ok/*.*' );

if ( count( $films ) ) {
	foreach ( $films as $film ) {
		$name = basename( $film );

		$allocine_id = str_replace( '.'.end( explode( '.', $name ) ), '', $name );

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

			echo "$Allocine->allocine_titre\n";

			$new_file_name = '';

			if ( $img = file::get( $Allocine->allocine_imageBig ) ) {
				$extention = end( explode( '.', $Allocine->allocine_imageBig ) );

				$new_file_dir = __DIR__.'/../app/module/video/upload/';
				$new_file_name = "$allocine_id.$extention";

				if ( file_exists( $new_file_dir.$new_file_name ) ) {
					unlink( $new_file_dir.$new_file_name );
				}

				file::put( $new_file_dir.$new_file_name, $img );
			}

			if ( !empty( $Allocine->allocine_titre ) ) {
				mysql_query( "INSERT INTO video ( `allocine_id`, `title`, `date`, `image` ) VALUES ( $Allocine->allocine_id, '".mysql_escape_string( $Allocine->allocine_titre )."', NOW(), '$new_file_name' )" );
			}
		}
		elseif ( isset( $_GET['all'] ) && !empty( $_GET['all'] ) ) {
			$Allocine = new AllocineModel( $allocine_id );
			$Allocine->getTitle();
			$Allocine->getImagesBig();

			echo "$Allocine->allocine_titre\n";

			$new_file_name = '';

			if ( $img = file::get( $Allocine->allocine_imageBig ) ) {
				$extention = end( explode( '.', $Allocine->allocine_imageBig ) );

				$new_file_dir = __DIR__.'/../app/module/video/upload/';
				$new_file_name = "$allocine_id.$extention";

				if ( file_exists( $new_file_dir.$new_file_name ) ) {
					unlink( $new_file_dir.$new_file_name );
				}

				file::put( $new_file_dir.$new_file_name, $img );
			}

			mysql_query( "UPDATE `video` SET `title` = '".mysql_escape_string( $Allocine->allocine_titre )."', `image` = '$new_file_name' WHERE `allocine_id` = $Allocine->allocine_id" );
		}
	}
}

include 'miss.php';