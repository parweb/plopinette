<?php

header("Content-type: text/xml; charset: utf-8");

$rss .= "<?xml version=\"1.0\" ?>\n";

$rss .= "<rss version=\"2.0\" xmlns:dc=\"http://purl.org/dc/elements/1.1/\" xmlns:atom=\"http://www.w3.org/2005/Atom\">\n";
$rss .= "<channel>\n";
$rss .= '<atom:link href="http://'.$_SERVER['HTTP_HOST'].link::href( 'actu', 'rss' ).'" rel="self" type="application/rss+xml" />'."\n";

ob_start();
	include $this->render( url('module'), url('action') );
$rss .= ob_get_contents();
ob_end_clean();

$rss .= "</channel>\n";
$rss .= "</rss>\n";

echo $rss;exit;

$xml = simplexml_load_string( $rss );

echo '<pre>';
	print_r( $xml );
echo '</pre>';