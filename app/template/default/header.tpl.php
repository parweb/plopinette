<div id="header-top">
	<div>
		<div id="logo">
			<a href="<?= link::href() ?>">Dailymatons.com</a>
		</div>

		<? if ( $is_login = user::is_login() ) : ?>
			<? $class_userbox = 'login'; ?>
		<? else : ?>
			<? $class_userbox = 'logout'; ?>
		<? endif; ?>

		<div id="userbox" class="<?= $class_userbox ?>">
			<div id="recommendation">
				<ul>
					<li><a href="<?= link::href( '/page/feedback/' ) ?>">?</a></li>
				</ul>
			</div>

			<div id="search">
				<form method="post" action="<?= link::href( '/video/search/' ) ?>">
					<input type="text" class="text" name="q" value="" placeholder="Looper, skyfall, ..." />
					<input type="submit" class="submit" value="Rechercher" />
				</form>
			</div>

			<? if ( $is_login ) : ?>
				<a class="account" href="#">&#128100; <span><?= user::login() ?></span></a>
				<? if ( user::is_admin() ) : ?><a href="<?= link::href( 'alert/list' ) ?>"><?= alert::count() ?> Alertes</a><? endif ?>
			<? else : ?>
				<a href="<?= link::href( '/user/login/?back='.urlencode( link::get() ) ) ?>" class="login">Connection / Inscription</a>
			<? endif; ?>
		</div>

		<div class="clear"></div>
	</div>
</div>

<div class="clear"></div>
