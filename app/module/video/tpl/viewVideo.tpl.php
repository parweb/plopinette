<? $view = true ?>

<? if ( user::get('status') == 'member' && !video::watched( $this->Video->id ) ) : ?>
	<? $last_buy = end( user::get('buy') ) ?>

	<? $nbWeek = config('subscription.free') ?>

	<? $time_2week = strtotime( "+$nbWeek week", strtotime( $last_buy->date ) ) ?>

	<? if ( $time_2week > time() ) : ?>
		<? $view = false ?>

		<div class="no_more center"><div>
			<p>vous avez atteint le quota de votre compte gratuit</p>
			<p>Vous pourrez voir un nouveau film <strong>dans <?= date::between( $time_2week, $last_buy->date, 'day' ) ?></strong></p>
			<br />
			<p>Souhaitez vous passer en <strong>illimité pour seulement 2€/mois</strong> ?</p>

			<div class="button">
				<a class="no" href="<?= link::href( 'video', 'seen' ) ?>">revoir un film</a>
				<a class="yes" href="<?= link::href( 'user', 'edit' ) ?>">oui</a>
			</div>
		</div></div>
	<? elseif ( isset( $_GET['confirm'] ) ) : ?>
		<? $view = true ?>
	<? elseif ( !isset( $_GET['confirm'] ) ) : ?>
		<? $view = false ?>

		<div class="confirm center"><div>
			<p>Attention vous etes un membre gratuit.</p>
			<p>Ce qui vous permet de voir <strong>1 films toutes les <?= $nbWeek ?> semaines</strong></p>
			<br />
			<p>Souhaitez vous vraiment voir ce film maintenant ?</p>

			<div class="button">
				<a class="no" href="<?= link::href( 'video', 'list' ) ?>">retour aux films</a>
				<a class="ajax yes" href="<?= URL.URI ?>/?confirm">je confirm</a>
			</div>
		</div></div>
	<? endif ?>
<? endif ?>

<? if ( $view ) : ?>
	<?= video::get( $this->Video->allocine_id, '711px', '400px' ) ?>
	<? video::view( $this->Video->id ); ?>
<? endif ?>