<div class="step">
	<h1>Ouvrir un compte</h1>

	<form class="login" method="post" action="<?= link::href( 'user/login' ) ?>">
		<h2>Connection</h2>

		<a href="<?= link::href( '/user/login/' ) ?>" class="button facebook-connect">Utiliser facebook</a>

		<div class="or hz"><span class="separator"><span class="word">ou</span></span></div>

		<fieldset>
			<?= form::text( 'login.user.login' ) ?>
			<?= form::password( 'login.user.pass' ) ?>
		</fieldset>
		
		<?= form::hidden( 'back', $_GET['back'] ) ?>

		<?= form::submit( 'Connection' ) ?>
	</form>

	<div class="or"><span class="separator"><span class="word">ou</span></span></div>

	<form class="right register" method="post" action="<?= link::href( 'user/add' ) ?>">
		<h2>Inscription</h2>

		<a href="<?= link::href( '/user/login/' ) ?>" class="button facebook-connect">Utiliser facebook</a>

		<div class="or hz"><span class="separator"><span class="word">ou</span></span></div>

		<fieldset>
			<?= form::text( 'add.user.email' ) ?>
			<?= form::text( 'add.user.login' ) ?>
			<?= form::password( 'add.user.pass' ) ?>
		</fieldset>

		<?= form::hidden( 'action', 'add_user' ) ?>

		<?= form::submit( 'S\'inscrire' ) ?>
	</form>

	<div class="clear"></div>
</div>