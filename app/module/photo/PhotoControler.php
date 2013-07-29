<?php

class PhotoControler extends AppControler {
	public function getAction () {
		$module = url( ':module' );
		$controler = ucfirst( $module ).'Controler';

		$dir = $controler::upload();
		$name = url( ':img' );

		$file = $dir.$name;

		list( $width, $height ) = getimagesize( $file );

		$width = ( url( ':width' ) ) ? url( ':width' ) : $width;
		$height = ( url( ':height' ) ) ? url( ':height' ) : $height;

		$path = DIR.APP.TMP.md5( $file.$width.$height ) );

		$Photo = $this->crop( new img( $file ), $width, $height );
		$Photo->save( $path );

		return $path;
	}

	public function crop ( $img, $w, $h ) {
		$cx = $img->getWidth() / 2;
		$cy = $img->getHeight() / 2;

		$x = $cx - $w / 2;
		$y = $cy - $h / 2;

		if ( $x < 0 ) $x = 0;
		if ( $y < 0 ) $y = 0;

		return $img->crop( $x, $y, $w, $h );
	}
}