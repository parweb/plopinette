<? $sql = sql::query( "SELECT * FROM menu_genre WHERE status = 1" ); ?>
<? $genres = $sql->fetchAll(); ?>

<? $sql = sql::query( "SELECT * FROM menu_lang WHERE status = 1" ); ?>
<? $langs = $sql->fetchAll(); ?>

<? $sql = sql::query( "SELECT * FROM menu_date" ); ?>
<? $dates = $sql->fetchAll(); ?>

<ul id="menu">
	<li class="top">
		<a href="<?= link::href( 'video' ) ?>">Genres</a>

		<ul class="sub">
			<? foreach ( $genres as $i => $item ) : ?>
				<li><a href="<?= link::href( "genre/id:$item[id]/" ) ?>"><?= $item['title'] ?><small><?= $item['count'] ?></small></a></li>
			<? endforeach; ?>

			<div class="clear"></div>
		</ul>
	</li>

	<li class="top">
		<a href="<?= link::href( 'video' ) ?>">Langues</a>

		<ul class="sub">
			<? foreach ( $langs as $i => $item ) : ?>
				<li><a href="<?= link::href( "lang/id:$item[id]/" ) ?>"><?= $item['title'] ?><small><?= $item['count'] ?></small></a></li>
			<? endforeach; ?>

			<div class="clear"></div>
		</ul>
	</li>

	<li class="top">
		<a href="<?= link::href( 'video' ) ?>">Années</a>

		<ul class="sub">
			<? foreach ( $dates as $i => $item ) : ?>
				<li><a href="<?= link::href( "date/d:$item[year]/" ) ?>"><?= $item['year'] ?><small><?= $item['count'] ?></small></a></li>
			<? endforeach; ?>

			<div class="clear"></div>
		</ul>
	</li>

	<? if ( user::is('admin') && 0 ) : ?>
		<li class="admin">
			<a href="#" class="top-level">Admin<span></span></a>
			<ul>
				<li><a href="<?= link::href( 'user/logout' ) ?>">Se déco</a></li>
				<li><a href="<?= link::href( 'user/edit' ) ?>">Mon compte</a></li>
				<li><a href="<?= link::href( 'page/list' ) ?>">Les pages</a></li>
				<li><a href="<?= link::href( 'menu/list' ) ?>">Les menus</a></li>
				<li><a href="<?= link::href( 'user/list' ) ?>">Les utilisateurs</a></li>
			</ul>
		</li>
	<? endif; ?>

	<? if ( user::is('admin') ) : ?>
			<li class="admin"><a href="<?= link::href( 'user/logout' ) ?>">Se déco</a></li>
			<li class="admin"><a href="<?= link::href( 'user/edit' ) ?>">Mon compte</a></li>
			<li class="admin"><a href="<?= link::href( 'page/list' ) ?>">Les pages</a></li>
			<li class="admin"><a href="<?= link::href( 'menu/list' ) ?>">Les menus</a></li>
			<li class="admin"><a href="<?= link::href( 'user/list' ) ?>">Les utilisateurs</a></li>
	<? endif; ?>
</ul>