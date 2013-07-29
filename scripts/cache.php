<?php

$params = include __DIR__.'/../app/config/params.php';

$tmp = array_merge(
	glob( __DIR__.'/../app/tmp/*' ),
	glob( __DIR__.'/../app/tmp/cache/*' ),
	glob( __DIR__.'/../app/tmp/assets/*' )
);

foreach ( $tmp as $file ) {
	if ( is_file( $file ) ) {
		$now = strtotime( '-'.$params['site']['cache'].' minutes' );

		if ( filemtime( $file ) < $now ) {
			unlink( $file );
		}
	}
}