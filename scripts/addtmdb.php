<?php

$config = include __DIR__.'/../app/config/bdd.php';
$params = include __DIR__.'/../app/config/params.php';

$bdd = $config['bdd'][$params['site']['env']];

mysql_connect( $bdd['host'], $bdd['user'], $bdd['mdp'] );
mysql_select_db( $bdd['base'] );

if ( isset( $_POST['rename'] ) ) {
	foreach ( $_POST['id'] as $id => $tmdb_id ) {
		if ( !empty( $id ) && !empty( $tmdb_id ) && $tmdb_id > 0 && $id > 0 ) {
			mysql_query( "UPDATE `dailymatons`.`video` SET `tmdb_id` = $tmdb_id WHERE `video`.`id` = $id" );
		}
	}
}

$sql = "SELECT * FROM `video` WHERE `status` = 1 AND `tmdb_id` = '' ORDER BY `video`.`view` DESC";
$query = mysql_query( $sql );

$dir = '/home/downloads/ok/';

$formats = array( 'avi', 'mkv' );

foreach ( $formats as $format ) {
	foreach ( glob( $dir.'*.'.$format ) as $file ) {
		$id = basename( $file, '.'.$format );

		if ( file_exists( $dir.$id.'.mp4' ) ) {
			unlink( $file );
		}
	}
}

?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>

<style>
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
		position: fixed;
		right: 0;
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
</style>

<form method="post" action="">
	<?

	while ( $item = mysql_fetch_assoc( $query ) ) {
		$name_clean = $item['title'];
		$img = glob( __DIR__.'/../app/module/video/upload/'.$item['allocine_id'].'*' );

		echo '<p data-name="'.$name_clean.'">';
			echo '<img align="absmiddle" height="200px" src="http://www.dailymatons.com/app/module/video/upload/'.basename( end( $img ) ).'" />';
			echo '<input size="100" type="text" value="'.$name_clean.'" /><br />';
			echo '<input size="100" type="text" value="'.$item['title_o'].'" /><br />';
			echo '<input type="text" value="'.$item['release'].'" /><br />';
			echo '<input class="text" type="text" name="id['.$item['id'].']" />';
			echo '<input type="submit" value="ok" />';
		echo '</p>';
	}

	?>

	<input type="hidden" name="rename" value="rename" />
</form>

<iframe src=""></iframe>

<script type="text/javascript">
	$('form p input').on( 'keyup', function(){
		var $this = $(this);
		var name = $this.val();

		var url_allocine = 'http://www.dailymatons.com/scripts/tmdb.php?s='+name;

		$('iframe').attr( 'src', url_allocine );

		console.log(name);
	});
</script>