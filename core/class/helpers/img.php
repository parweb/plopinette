<?php

class img {
	protected $width;
	protected $height;
	protected $image;

	public function __construct ( $file = null ) {
		if ( null !== $file ) {
			if ( is_file( $file ) ) {
				$this->setImageFile( $file );
			} else {
				$this->setImageString( $file );
			}
		}
	}

	public function setImageFile ( $file ) {
		if ( ! ( is_readable( $file ) && is_file( $file ) ) ) {
			throw new InvalidArgumentException( "Image file $file is not readable" );
		}

		if ( is_resource( $this->image ) ) {
			imagedestroy( $this->image );
		}

		list( $this->width, $this->height, $type ) = getimagesize( $file );

		switch ( $type ) {
			case IMAGETYPE_GIF :
				$this->image = imagecreatefromgif( $file );
				break;
			case IMAGETYPE_JPEG :
				$this->image = imagecreatefromjpeg( $file );
				break;
			case IMAGETYPE_PNG :
				$this->image = imagecreatefrompng( $file );
				break;
			default	:
				throw new InvalidArgumentException( "Image type $type not supported" );
		}

		return $this;
	}

	public function setImageString ( $data ) {
		if ( is_resource( $this->image ) ) {
			imagedestroy( $this->image );
		}

		if ( !$this->image = imagecreatefromstring( $data ) ) {
			throw new RuntimeException( 'Cannot create image from data string' );
		}
		$this->width = imagesx( $this->image );
		$this->height = imagesy( $this->image );

		return $this;
	}

	public function resample ( $width, $height, $constrainProportions = true ) {
		if ( !is_resource( $this->image ) ) {
			throw new RuntimeException( 'No image set' );
		}
		if ( $constrainProportions ) {
			if ( $this->height >= $this->width ) {
				$width = round( $height / $this->height * $this->width );
			} else {
				$height = round( $width / $this->width * $this->height );
			}
		}

		$temp = imagecreatetruecolor( $width, $height );
		imagecopyresampled( $temp, $this->image, 0, 0, 0, 0, $width, $height, $this->width, $this->height );

		return $this->_replace( $temp );
	}

	public function enlargeCanvas ( $width, $height, array $rgb = array() , $xpos = null, $ypos = null ) {
		if ( !is_resource( $this->image ) ) {
			throw new RuntimeException( 'No image set' );
		}

		$width = max( $width, $this->width );
		$height = max( $height, $this->height );

		$temp = imagecreatetruecolor( $width, $height );
		if ( count( $rgb ) == 3 ) {
			$bg = imagecolorallocate( $temp, $rgb[0], $rgb[1], $rgb[2] );
			imagefill( $temp, 0, 0, $bg );
		}

		if ( null === $xpos ) {
			$xpos = round( ( $width - $this->width ) / 2 );
		}

		if ( null === $ypos ) {
			$ypos = round( ( $height - $this->height ) / 2 );
		}

		imagecopy( $temp, $this->image, (int) $xpos, (int) $ypos, 0, 0, $this->width, $this->height );

		return $this->_replace( $temp );
	}

	public function crop ( $x1, $y1 = 0, $x2 = 0, $y2 = 0 ) {
		if ( !is_resource( $this->image ) ) {
			throw new RuntimeException( 'No image set' );
		}

		if ( is_array( $x1 ) && 4 == count( $x1 ) ) {
			list( $x1, $y1, $x2, $y2 ) = $x1;
		}

		$x1 = max( $x1, 0 );
		$y1 = max( $y1, 0 );

		$x2 = min( $x2, $this->width );
		$y2 = min( $y2, $this->height );

		$width = $x2 - $x1;
		$height = $y2 - $y1;

		$temp = imagecreatetruecolor( $width, $height );
		imagecopy( $temp, $this->image, 0, 0, $x1, $y1, $width, $height );

		return $this->_replace( $temp );
	}

	protected function _replace ( $res ) {
		if ( !is_resource( $res ) ) {
			throw new UnexpectedValueException( 'Invalid resource' );
		}

		if ( is_resource( $this->image ) ) {
			imagedestroy( $this->image );
		}

		$this->image = $res;
		$this->width = imagesx( $res );
		$this->height = imagesy( $res );

		return $this;
	}

	public function save ( $fileName, $type = IMAGETYPE_JPEG ) {
		$dir = dirname( $fileName );
		if ( !is_dir( $dir ) ) {
			if ( !mkdir( $dir, 0755, true ) ) {
				throw new RuntimeException( 'Error creating directory '.$dir );
			}
		}
		try {
			switch ( $type ) {
				case IMAGETYPE_GIF :
					if ( !imagegif( $this->image, $fileName ) ) {
						throw new RuntimeException;
					}
					break;
				case IMAGETYPE_PNG :
					if ( !imagepng( $this->image, $fileName ) ) {
						throw new RuntimeException;
					}
					break;
				case IMAGETYPE_JPEG :
				default			 :
					if ( !imagejpeg( $this->image, $fileName, 95 ) ) {
						throw new RuntimeException;
					}
			}
		} catch ( Exception $ex ) {
			throw new RuntimeException( 'Error saving image file to '.$fileName );
		}
	}

	public function getResource () {
		return $this->image;
	}

	public function getWidth () {
		return $this->width;
	}

	public function getHeight () {
		return $this->height;
	}

	static public function get ( $module, $img, $w = null, $h = null ) {
		$controler = ucfirst( $module ).'Controler';

		$dir = $controler::upload();
		$name = $img;

		$file = $dir.$name;

		if ( $w && $h && getimagesize( $file ) ) {
			$path = APP.TMP.md5( $file.$w.$h );

			if ( !file_exists( DIR.$path ) ) {
				$img = new self( $file );
				$Photo = $img->resample( $w, $h, true );

				$Photo->save( DIR.$path );
			}

			return URL.$path;
		}
		else {
			return str_replace( DIR, URL, $file );
		}
	}

	static public function get2 ( $module, $img, $w = null, $h = null ) {
		$controler = ucfirst( $module ).'Controler';

		$dir = $controler::upload();
		$name = $img;

		$file = $dir.$name;

		list( $width, $height ) = getimagesize( $file );

		$width = ( $w ) ? $w : $width;
		$height = ( $h ) ? $h : $height;

		$path = APP.TMP.md5( $file.$width.$height );


		//if ( !file_exists( DIR.$path ) ) {
			$Photo = self::cropCenter( new self( $file ), $width, $height );
			$Photo->save( DIR.$path );
		//}

		return URL.$path;
	}

	static public function cropCenter ( $img, $w, $h ) {
		$path = APP.TMP.md5( $file.$w.$h );

		//if ( !file_exists( DIR.$path ) ) {
			$Photo = $img->resample( $w, $h, true );
			$Photo->save( DIR.$path );

			$img = new self( DIR.$path );

			$cx = $img->getWidth() / 2;
			$cy = $img->getHeight() / 2;

			$x = $cx - $w / 2;
			$y = $cy - $h / 2;

			if ( $x < 0 ) $x = 0;
			if ( $y < 0 ) $y = 0;

			$w = $x + $w;
			$h = $y + $h;

			return $img->crop( $x, $y, $w, $h );
		//}

		return URL.$path;
	}
}