<?php

include_once 'core/class/helpers/s3.php';

$s3 = new s3( awsAccessKey, awsSecretKey );

$films = glob( '/home/downloads/*.*' );

// Get the contents of our bucket
$getBucket = $s3->getBucket( bucket );
$films_uploaded = array();
foreach ( $getBucket as $file ) {
	$films_uploaded[] = $file['name'];
}

if ( count( $films ) ) {
	foreach ( $films as $film ) {
		if ( preg_match( '#^([0-9]*)\..*$#', basename( $film ) ) ) {
			$file = $film;
			$name = basename( $film );

			echo $name;

			if ( !in_array( $name, $films_uploaded ) ) {
				if ( $s3->putObjectFile( $file, bucket, $name, s3::ACL_PUBLIC_READ_WRITE ) ) {
					echo " - OK\n";
				}
			}
			else {
				unlink( $file );
				echo " - KO\n";
			}
		}
	}
}