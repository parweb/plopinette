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
	$sql = "SELECT id, allocine_id FROM video WHERE status = 1 ORDER BY RAND() LIMIT 0, 10";
	//$sql = "SELECT id, allocine_id FROM video WHERE status = 1 ORDER BY RAND()";
}

$req = mysql_query( $sql );

while ( $item = mysql_fetch_assoc( $req ) ) {
	$allocine_id = $item['allocine_id'];
	$id = $item['id'];

	$Allocine = new AllocineModel( $allocine_id );
	$Allocine->getInfos();

	echo "$id - $Allocine->allocine_titre\n";

	// maj video
	if ( !empty( $Allocine->allocine_titreOriginal ) ) mysql_query( "UPDATE `video` SET `title_o` = '".mysql_escape_string( $Allocine->allocine_titreOriginal )."' WHERE `video`.`allocine_id` = $allocine_id" );
	if ( $Allocine->allocine_dateSortie != '0000-00-00' ) mysql_query( "UPDATE `video` SET `release` = '".mysql_escape_string( $Allocine->allocine_dateSortie )."' WHERE `video`.`allocine_id` = $allocine_id" );
	if ( !empty( $Allocine->allocine_duree ) ) mysql_query( "UPDATE `video` SET `duration` = '".mysql_escape_string( $Allocine->allocine_duree )."' WHERE `video`.`allocine_id` = $allocine_id" );
	if ( !empty( $Allocine->allocine_synopsis ) ) mysql_query( "UPDATE `video` SET `abstract` = '".mysql_escape_string( $Allocine->allocine_synopsis )."' WHERE `video`.`allocine_id` = $allocine_id" );
	if ( !empty( $Allocine->allocine_trailer ) ) mysql_query( "UPDATE `video` SET `trailer` = '".mysql_escape_string( $Allocine->allocine_trailer )."' WHERE `video`.`allocine_id` = $allocine_id" );

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
}