<? if ( url('ajax') ) : ?>
	<? include $this->render( url('module'), url('action') ); ?>
<? else : ?>
<html>
	<? include $this->layout( 'head' ); ?>

	<body class="_<?= rand( 0, 8 ) ?>" module="<?= url('module') ?>" action="<?= url('action') ?>" id="<?= _::clean( url('module').'_'.url('action') ); ?>"<? $more_module = ( url('admin') ) ? ' class="admin"' : ''; $more_module = ( url('ajax') ) ? ' class="ajax"' : ''; echo $more_module; ?>>
		<div id="fb-root"></div>

		<div id="container">
			<div id="header">
				<? include $this->layout( 'header' ); ?>
			</div>

			<div id="searchbar">
				<form method="post" action="<?= link::href( 'video/search' ) ?>">
					<input type="text" name="q" value="" class="text" />
					<input type="submit" value="" class="submit" />

					<div class="clear"></div>
				</form>
			</div>

			<div id="menu">
				<ul>
					<li><a href="<?= link::href( 'video/top' ) ?>">Top</a></li>
					<li><a href="<?= link::href( 'video/last' ) ?>">Last</a></li>
					<li><a href="<?= link::href( 'video/trend' ) ?>">Trend</a></li>

					<div class="clear"></div>
				</ul>
			</div>

			<div id="content">
				<div id="content-in">
					<div id="title">
						<h1><?= $title; ?></h1>
						<? $momomodule = ( url('module') == 'resultat' &&  url('action') == 'list' ) ? 'participation' : url('module'); ?>

						<? if ( user::is_admin() || user::is_secretaire() ) : ?>
							<div id="admin-bar">
								<?= button::link( 'Liste', link::href( url('module'), 'list' ) ) ?>
								<?= button::link( '+ Ajouter', link::href( $momomodule, 'add' ) ) ?>

								<? if ( url(':id') ) : ?>
									<?=  ' '.button::link( 'Edit', link::href( url('module'), 'edit', array( 'id' => url(':id') ) ) ) ?>
									<?=  ' '.button::link( '- Supprimer', link::href( url('module'), 'delete', array( 'id' => url(':id') ) ) ) ?>
								<? endif; ?>
							</div>
						<? endif; ?>

						<div class="clear"></div>
					</div>

					<div class="<?= url('module') ?> <?= url('action') ?>">
						<? include $this->render( url('module'), url('action') ); ?>
					</div>
				</div>
			</div>

			<div id="footer">
				<? include $this->layout( 'footer' ); ?>
			</div>

			<div class="clear"></div>
		</div>
	</body>

	<? //include $this->layout( 'analytics' ); ?>
</html>
<? endif; ?>