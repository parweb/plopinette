<?php

$token = 'api_key=5f06a6c70d8a745836f7e2e71b41618f';

/*

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://api.themoviedb.org/3/movie/$_GET[id]/images?$token");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json"));
$response = curl_exec($ch);
curl_close($ch);
 
$images = json_decode( $response, true );

foreach ( $images['backdrops'] as $item ) {
	echo "<img width='20%' src='http://cf2.imgobject.com/t/p/original$item[file_path]' />";
}

*/

echo "<form method='get'>
	<input type='text' id='s' name='s' value='$_GET[s]' />
</form>";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://api.themoviedb.org/3/search/movie?query=".urlencode( $_GET['s'] )."&$token");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json"));
$response = curl_exec($ch);
curl_close($ch);

$result = json_decode( $response, true );

foreach ( $result['results'] as $item ) {
	echo "<p style='font-size: 30px;'><img align='absmiddle' height='200px' src='http://cf2.imgobject.com/t/p/original$item[poster_path]' /> $item[id] - $item[title] <small>$item[release_date]</small></p>";
}