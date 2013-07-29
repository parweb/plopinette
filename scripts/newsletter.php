<?php

// Nouveautés et informations sur dailymatons.com - le streaming simplifiez

$config = include __DIR__.'/../app/config/bdd.php';
$params = include __DIR__.'/../app/config/params.php';

$bdd = $config['bdd'][$params['site']['env']];

mysql_connect( $bdd['host'], $bdd['user'], $bdd['mdp'] );
mysql_select_db( $bdd['base'] );

$sql = "SELECT * FROM user WHERE id NOT IN ( SELECT `user_id` FROM `buy` GROUP BY `user_id` HAVING COUNT(*) > 10 ORDER BY COUNT(*) ASC ) AND status = 1";
$sql = "SELECT * FROM user WHERE status = 1";

$req = mysql_query( $sql );

$csv = array();

while ( $item = mysql_fetch_assoc( $req ) ) {
	// Array
	// (
	//     [id] => 3
	//     [facebook_id] => 0
	//     [login] => popopo
	//     [pass] => c8bc6a919e61fd6bf9407c5ce3adbdf0
	//     [sessioncode] => def2ea46a3c7b22714cf66683127895a
	//     [email] => nico@yopmail.com
	//     [status] => 1
	//     [date] => 2012-05-08 14:54:17
	//     [tickets] => 991
	//     [actions] => 0
	// );

	if ( !count( $csv ) ) {
		$csv[] = join( ';', array_keys( $item ) );
	}

	$csv[] = join( ';', array_values( $item ) );
}

exit( join( "\n", $csv ) );