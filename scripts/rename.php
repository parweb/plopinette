<?php

if ( isset( $_POST['rename'] ) ) {
	$config = include __DIR__.'/../app/config/bdd.php';
	$params = include __DIR__.'/../app/config/params.php';

	$bdd = $config['bdd'][$params['site']['env']];

	mysql_connect( $bdd['host'], $bdd['user'], $bdd['mdp'] );
	mysql_select_db( $bdd['base'] );

	foreach ( $_POST['id'] as $file => $id ) {
		if ( !empty( $id ) ) {
			$dir = dirname( $file ).'/';
			$ext = '.'.end( explode( '.', $file ) );

			$newname = strtolower( '/home/downloads/ok/'.$id.$ext );

			rename( $file, $newname );

			mysql_query( "UPDATE video SET date = 'NOW()' WHERE id = $id" );
		}
	}
}

$_films = glob( '/home/downloads/*.*' );

$films = array();
foreach	( $_films as $film ) {
	if ( preg_match( '#\.avi$#', $film ) || preg_match( '#\.mp4$#', $film ) || preg_match( '#\.mkv$#', $film ) || preg_match( '#\.m4v$#', $film ) || preg_match( '#\.divx$#', $film ) ) {
		$films[] = $film;
	}
}

echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>';

echo '<style>
	* {
		margin: 0; padding: 0;
		border: none;
	}

	form, iframe {
		float: left;
		width: 45%;
		z-index: 100;
	}

	iframe {
		width: 55%;
		height: 100%;
		z-index: 1000;
	}

	input {
		border: 1px solid black;
		margin: 4px;
	}

	input.text {
	width: 400px;
	}
</style>';

echo '<form method="post">';
	foreach	( $films as $film ) {
		$basename_film = basename( $film );

		$id = str_replace( '.'.end( explode( '.', $basename_film ) ), '', $basename_film );

		if ( !preg_match( '#^([0-9]*)$#', $id ) && !is_dir ( $film ) ) {
			if ( preg_match( '#\.avi$#', $film ) ) {
				$background = 'green';
				$color = 'white';
			}
			elseif ( preg_match( '#\.mkv$#', $film ) ) {
				$background = 'orange';
				$color = 'white';
			}
			elseif ( preg_match( '#\.mp4$#', $film ) ) {
				$background = 'red';
				$color = 'white';
			}

			$style = ' style="padding: 5px; display: inline-block; width: 100%; background: '.$background.'; color: '.$color.';"';

			$name_clean = str_replace( array( '.', '_', '-' ), array( ' ', ' ', ' ' ), basename( $film ) );
			$name_clean = str_replace( array( 'avi', 'DIVX', 'DvDRiP', 'DVDRiP', 'XViD', 'TRUEFRENCH', 'AC3', 'LiMiTED', 'SUBFORCED', 'TVRip', 'VOSTFR', 'FRENCH', 'Xvid', 'Ac3', 'BRRIP', 'France5', 'FESTiVAL', 'DVDRip', 'XviD', 'SubForced', 'No Tag', 'BRRiP', 'DvdRip', 'SubForced', 'STV', 'Vostfr', 'dvdrip', '1CD', '2CD', 'BDRip', 'hdtv', 'xvid', 'DVDRIP', 'XVID', 'HDTV', 'DvDRip' ), '', $name_clean );

			$name_clean = preg_replace( '#\s+#', ' ', $name_clean );

			echo '<p data-name="'.$name_clean.'">';
				echo '<label'.$style.'><input size="100" type="text" value="'.$name_clean.'" /></label><br />';
				echo '<input class="text" type="text" name="id['.$film.']" />';
				echo '<input type="submit" value="ok" />';
			echo '</p>';
		}
	}

	echo '<input type="hidden" name="rename" value="rename" />';
echo '</form>';

?>

<iframe src="http://www.allocine.com/"></iframe>

<script type="text/javascript">
	$('form p label input').on( 'keyup', function(){
		var $this = $(this);
		var name = $this.val();

		var url_allocine = 'http://www.allocine.fr/recherche/?q='+name;

		$('iframe').attr( 'src', url_allocine );

		console.log(name);
	});
</script>

<? include __DIR__.'/insert.php' ?>
<? include __DIR__.'/search.php' ?>
<? include __DIR__.'/cache.php' ?>