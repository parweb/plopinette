<?php

function errorLog ( $msg ) {
	// disable error log if we are running in a CLI environment
	// @codeCoverageIgnoreStart
	if ( php_sapi_name() != 'cli' ) {
		error_log( $msg );
	}

	// uncomment this if you want to see the errors on the page
	// print 'error_log: '.$msg."\n";
	// @codeCoverageIgnoreEnd
}

function curl ( $uri, $params, $token = null ) {
	echo "$uri\n";
	var_dump( $token );

	if ( $token != 'none' ) {
		$token = connect( '', '' );
	}

	$URL = 'https://api.t411.me/';

	$CURL_OPTS = array(
		CURLOPT_CONNECTTIMEOUT => 10,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT => 60,
		CURLOPT_USERAGENT => 'User Agent',
	);

	if ( !$ch ) {
		$ch = curl_init();
	}

	$opts = $CURL_OPTS;

	if ( count( $params ) ) $opts[CURLOPT_POSTFIELDS] = http_build_query( $params, null, '&' );
	$opts[CURLOPT_URL] = $URL.$uri;
	if ( !is_null( $token ) && $token != 'none' ) $opts[CURLOPT_HTTPHEADER] = array( 'Authorization: '.$token );

	print_r( $opts );

	curl_setopt_array( $ch, $opts );
	$result = curl_exec( $ch );

	if ( curl_errno( $ch ) == 60 ) { // CURLE_SSL_CACERT
		errorLog( 'Invalid or no certificate authority found, using bundled information' );

		curl_setopt( $ch, CURLOPT_CAINFO, __DIR__.'/encrypt.crt' );

		$result = curl_exec( $ch );
	}

	curl_close( $ch );

	return json_decode( $result, true );
}

function connect ( $user, $pass ) {
	$res = curl( 'auth/', array( 'username' => $user, 'password' => $pass ), 'none' );

	if ( isset( $res['token'] ) ) {
		$return = $res['token'];
	}
	else {
		$return = null;
	}

	return $return;
}

// search
$list = curl( 'torrents/search/matrix?offset=0&limit=30' );

// search dans une categorie
$list = curl( 'torrents/search/avatar&cid=246' );

// Catégories
$list = curl( 'categories/tree/' );

// terms
$list = curl( 'terms/tree/' );

// détail d'un torrent
$list = curl( 'torrents/details/{id}' );

// download un torrent
$list = curl( 'torrents/download/{id}' );

// Top 100
$list = curl( 'torrents/top/100' );

// Top today
$list = curl( 'torrents/top/today' );

// Top week
$list = curl( 'torrents/top/week' );

// Top month
$list = curl( 'torrents/top/month' );

print_r( $list );
