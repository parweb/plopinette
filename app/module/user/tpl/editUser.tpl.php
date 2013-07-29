<div class="step">
	<h1>Votre compte</h1>

	<form class="login" method="post" action="<?= link::href( 'user/edit' ) ?>">
		<h2>Identifiants</h2>

		<fieldset>
			<?= form::text( 'edit.user.email', user::get('email') ) ?>
			<?= form::text( 'edit.user.login', user::get('login') ) ?>
			<?= form::password( 'edit.user.pass', '' ) ?>
		</fieldset>

		<?= form::hidden( 'edit.user.id', user::id() ) ?>

		<?= form::submit( 'Modifier' ) ?>
	</form>

	<form class="formule right" method="post" action="javascript:$('form .howto.desoler').slideDown('slow');">
		<h2>Formule<span><?= $status = user::get('status') ?></span></h2>

		<? if ( $status == 'member' ) : ?>
			<p>Vous avez le droit de visionner</p>
			<p>1 film tous les <?= config( 'subscription.free' ) ?> semaines</p>

			<?= form::submit( 'Obtenir un compte illimité pour 2€/semaine' ) ?>
		<? else : ?>
			<p>Vous avez le droit de visionner</p>
			<p>Toutes les films en illimité</p>
			<p>Valable encore x jours</p>

			<?= form::submit( 'Renouveler mon compte illimité' ) ?>
		<? endif; ?>
	</form>

	<div class="clear"></div>
</div>
<!--
<div class="step two">
	<h1>Choisissez votre formule</h1>

	<form class="free" method="get" action="<?= link::href( 'video' ) ?>">
		<h2>Compte gratuit</h2>

		<p>Vous permet de visionner</p>
		<p class="important">1 film tous les 15 jours</p>
		<p>Pour cela il suffit d'ouvrir un compte</p>

		<?= form::submit( 'Ouvrir un compte' ) ?>
	</form>

	<div class="or"><span class="separator"><span class="word">ou</span></span></div>

	<form class="right prémium" method="post" action="<?= link::href( 'user/add' ) ?>">
		<h2>Compte 2€/semaine</h2>

		<p>Vous permet de visionner</p>
		<p class="important">Tous les films en illimité</p>


		<?= form::submit( 'Devenir prémium' ) ?>
	</form>

	<div class="clear"></div>
</div>
-->
<form id="desoler" action="<?= link::href( 'user/desoler' ) ?>" method="post">
	<div class="howto desoler">
		<fieldset>
			<h1>+2500 films qualité HD</h1>

			<div class="prices">
				<? foreach ( config('subscription.prices') as $k => $v ) :  ?>
					<label <?= ( $k == 3 ) ? ' class="hover"' : '' ?>>
						<div class="mount"><?= $k ?> semaines</div>
						<div class="price" for="duration<?= $k ?>"><?= $v ?> €</div>
						<div class="radio"><input type="radio" name="duration" value="<?= $k ?>" id="duration<?= $k ?>" <?= ( $k == 3 ) ? ' checked="checked"' : '' ?>/></div>
					</label>
				<? endforeach; ?>

				<div class="clear"></div>
			</div>

			<div class="clear"></div>

			<?= form::hidden( 'action', 'subscribe' ) ?>

			<input class="form-submit" type="submit" value="devenir prémium maintenant">
		</fieldset>
	</div>
</form>


<div id="explain">
	<div class="howto">
		<h1>Parrainez vos amis et <b>gagner de l'argent !</b></h1>
		<div class="box">
			<h2>X parrainage prémium = X * 2/3 * <strong>2€</strong></h2>

			<ul>
				<li>3 parrainage prémium = <strong>4€</strong></li>
				<li>6 parrainage prémium = <strong>8€</strong></li>
				<li>9 parrainage prémium = <strong>12€</strong></li>
				<li>12 parrainage prémium = <strong>16€</strong></li>
				<li>...</li>
				<li>300 parrainage prémium = <strong>400€</strong></li>
			</ul>
		</div>

		<div class="box">
			<h2>
				<span class="legend">votre lien</span>
				<span>http://dailymatons.com/sponsorship/enjoy/i:<?= user::enjoy( user::id() ) ?>/</span>

				<div class="clear"></div>
			</h2>

			<ul>
				<li>Pas de frais cachet</li>
				<li>Pas besoin de compte prémium</li>
				<li>Vous garder vos <strong>filleule à vie</strong></li>
				<li><strong>Aucune limite de gain</strong></li>
				<li>Retrait possible, partez de <strong>0€</strong></li>
				<li>Questions ? <a href="mailto:<?= config( 'mail.contact.email' ) ?>"><?= config( 'mail.contact.email' ) ?></a></li>
			</ul>
		</div>

		<div class="clear"></div>
	</div>
</div>