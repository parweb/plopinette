<? if ( user::is_login() ) : ?>
	<div id="pageslide" class="right">
		<ul>
			<li><a href="<?= link::href( 'user/edit' ) ?>"><span>&#9998;</span>Editer votre compte</a></li>
			<li><a href="<?= link::href( 'video/seen' ) ?>"><span>&#127916;</span>Vidéos déjà vus</a></li>
			<li><a href="<?= link::href( 'user/collections' ) ?>"><span>&#9871;</span>Vos listes de films</a></li>
			<li><a href="<?= link::href( 'user/gains' ) ?>"><span>&#128200;</span>Vos gains</a></li>
			<li><a href="<?= link::href( 'user/logout' ) ?>"><span>&#59201;</span>Se déconnecter</a></li>
		</ul>
	</div>
<? endif; ?>