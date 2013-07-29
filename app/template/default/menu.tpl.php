<?

$genre = ( url('module') == 'genre' && url('action') == 'list' ) ? ' class="hover"' : '';
$boxoffice = ( url('order') == 'video.boxoffice DESC' ) ? ' class="hover"' : '';
$populaire = ( url('order') == 'video.view DESC' ) ? ' class="hover"' : '';
$new = ( url('order') == 'video.release DESC' ) ? ' class="hover"' : '';
$seen = ( url('module') == 'video' && url('action') == 'seen' ) ? ' class="hover"' : '';

?>

<div id="navigation">
	<ul>
		<li<?= $genre ?>><a href="<?= link::href( 'genre' ) ?>"><span>&#59148;</span>Genres</a></li>
		<li<?= $boxoffice ?>><a href="<?= link::href( '/video/list/boxoffice:desc/' ) ?>"><span>&#127942;</span>Boxoffice</a></li>
		<li<?= $populaire ?>><a href="<?= link::href( '/video/list/view:desc/' ) ?>"><span>&#128077;</span>Populaires</a></li>
		<li<?= $new ?>><a href="<?= link::href( '/video/list/release:desc/' ) ?>"><span>&#128340;</span>Nouveautés</a></li>
		<li<?= $seen ?>><a href="<?= link::href( 'video/seen' ) ?>"><span>&#127916;</span>Vidéos déjà vus</a></li>

		<div class="clear"></div>
	</ul>
</div>