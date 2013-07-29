<div id="header-top">
	<div>
		<div id="logo">
			<a href="<?= link::href() ?>">Dailymatons <span class="small">BETA</span></a>
		</div>

		<? if ( $is_login = user::is_login() ) : ?>
			<? $class_userbox = 'login'; ?>
		<? else : ?>
			<? $class_userbox = 'logout'; ?>
		<? endif; ?>

		<div id="userbox" class="<?= $class_userbox ?>">
			<div id="search">
				<form method="post" action="<?= link::href( '/video/search/' ) ?>">
					<input type="text" class="text" name="q" value="" />
					<input type="submit" class="submit" value="Rechercher" />
				</form>
			</div>

			<? if ( $is_login ) : ?>
				<a href="/">Bonjour, <?= user::get('login') ?> | tickets : <?= user::get('tickets') ?> | actions : <?= user::get('actions') ?></a>
			<? else : ?>
				<a href="<?= link::href( '/user/login/' ) ?>" class="facebook-connect login">Se connecter</a><a href="<?= link::href( '/user/add/' ) ?>" class="facebook-connect register">S'inscrire</a>
			<? endif; ?>
		</div>

		<div class="clear"></div>
	</div>
</div>

<div class="clear"></div>