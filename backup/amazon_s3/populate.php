<?php

include_once 'core/class/helpers/file.php';
include_once 'core/class/CoreModel.php';
include_once 'app/module/allocine/class/AllocineModel.php';

$config = include 'app/config/bdd.php';

mysql_connect( $config['bdd']['host'], $config['bdd']['user'], $config['bdd']['mdp'] );
mysql_select_db( $config['bdd']['base'] );

$sql = "SELECT id, allocine_id FROM video ORDER BY id ASC";
//$sql = "SELECT id, allocine_id FROM video WHERE id = 127 ORDER BY id ASC";
$req = mysql_query( $sql );

while ( $item = mysql_fetch_assoc( $req ) ) {
	$allocine_id = $item['allocine_id'];
	$id = $item['id'];

	echo $id;

	$Allocine = new AllocineModel( $allocine_id );
	$Allocine->getInfos( false );

	// maj video
	mysql_query( "UPDATE `video` SET `title_o` = '".mysql_escape_string( $Allocine->allocine_titreOriginal )."' WHERE `video`.`allocine_id` = $allocine_id" );
	mysql_query( "UPDATE `video` SET `release` = '".mysql_escape_string( $Allocine->allocine_dateSortie )."' WHERE `video`.`allocine_id` = $allocine_id" );
	mysql_query( "UPDATE `video` SET `duration` = '".mysql_escape_string( $Allocine->allocine_duree )."' WHERE `video`.`allocine_id` = $allocine_id" );
	mysql_query( "UPDATE `video` SET `abstract` = '".mysql_escape_string( $Allocine->allocine_synopsis )."' WHERE `video`.`allocine_id` = $allocine_id" );

	// add director
	foreach ( $Allocine->allocine_realisateur as $artist_id => $name ) {
		$identity = explode( ' ', $name );
		$identity[1] = str_replace( $identity[0].' ', '', $name );

		mysql_query( "INSERT INTO `artist` VALUES ( $artist_id, '".mysql_escape_string( $identity[0] )."', '".mysql_escape_string( $identity[1] )."', '', 1 )" );
		mysql_query( "INSERT INTO `director` VALUES ( '', $id, $artist_id )" );
	}

	// add actor
	foreach ( $Allocine->allocine_acteurs as $artist_id => $name ) {
		$identity = explode( ' ', $name );
		$identity[1] = str_replace( $identity[0].' ', '', $name );

		mysql_query( "INSERT INTO `artist` VALUES ( $artist_id, '".mysql_escape_string( $identity[0] )."', '".mysql_escape_string( $identity[1] )."', '', 1 )" );
		mysql_query( "INSERT INTO `actor` VALUES ( '', $id, $artist_id )" );
	}

	// add lang
	foreach ( $Allocine->allocine_langue as $lang_id => $name ) {
		mysql_query( "INSERT INTO `lang` VALUES ( $lang_id, '".mysql_escape_string( $name )."', 1 )" );
		mysql_query( "INSERT INTO `languer` VALUES ( '', $id, $lang_id )" );
	}

	// add genre
	foreach ( $Allocine->allocine_genre as $genre_id => $name ) {
		mysql_query( "INSERT INTO `genre` VALUES ( $genre_id, '".mysql_escape_string( $name )."', 1 )" );
		mysql_query( "INSERT INTO `genrer` VALUES ( '', $id, $genre_id )" );
	}

	echo " - OK\n";
}