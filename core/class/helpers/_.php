<?php

class _ {
	public static function breadcrump ( $array ) {
		if ( count( $array ) > 0 ) {
			$return = '<ul id="breadcrump">';
				foreach ( $array as $item ) {
					$return .= '<li>'.$item.'</li>';
				}
			$return .= '</ul>';
		}

		return $return;
	}

	public static function clean ( $string ) {
		$string = urlencode( $string );
		$string = strtolower( trim( $string ) );
		$string = strtr( $string, 'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ', 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY' );
		$string = str_replace( array( '_', ' ', '\'', ':', '(', ')', '°' ), '-', $string );
		$string = str_replace( array( '----------', '---------', '--------', '-------', '------', '-----', '----', '---', '--', '-'), '-', $string );

		return $string;
	}

	public static function crypt ( $string = '' ) {
		return md5( config('security.salt').$string );
	}

	public static function sentence ( $string ) {
		$sentences = explode( '.', trim( $string, '.' ));

		if ( count( $sentences ) > 0 ) {
			$paragraphe = '';

			foreach ( $sentences as $sentence ) {
				$words = explode( ' ', $sentence );
				$firs_word = ucfirst( $words[0] );

				if ( count( $words ) > 0 ) {
					$new_sentence = $firs_word.' ';

					foreach ( $words as $i => $le_word ) {
						if ( $i != 0 ) {
							$new_sentence .= $le_word;
						}

						if ( ( $i + 1 ) == count( $words ) ) {
							$new_sentence .= '. ';
						}
						else {
							$new_sentence .= ' ';
						}
					}

					$paragraphe .= $new_sentence;
				}
			}
		}

		$ponc_str = array( '?.', 	'!.', 	'? .', 	'! .', 	'....', 	'... .', 	' .', 	'. . . .', 	'... .' );
		$ponc_rep = array( '?', 	'!', 	'?', 	'!', 	'...', 		'...', 		'.', 	'...', 		'...' );

		$paragraphe = str_replace( $ponc_str, $ponc_rep, $paragraphe );
		return str_replace( $ponc_str, $ponc_rep, $paragraphe );
	}

	public static function t ( $string ) {
		$traductions = config('traductions');

		$return = $string;
		if ( isset( $traductions[$string] ) ) {
			$return = $traductions[$string];
		}

		return $return;
	}
}