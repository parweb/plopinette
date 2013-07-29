<? if ( url('ajax') ) : ?>
<? include $this->render( url('module'), url('action') ); ?>
<? else : ?>
<html>
	<? include $this->layout( 'head' ); ?>

	<body module="<?= url('module') ?>" action="<?= url('action') ?>" id="<?= _::clean( url('module').'_'.url('action') ); ?>"<? $more_module = ( url('admin') ) ? ' class="admin"' : ''; $more_module = ( url('ajax') ) ? ' class="ajax"' : ''; echo $more_module; ?>>
		<div id="fb-root"></div>

		<div id="container">
			<div id="header">
				<? include $this->layout( 'header' ); ?>
			</div>

			<div id="content">
				<div id="content-in">
					<? include $this->layout( 'menu' ); ?>

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

					<?
					if ( isset( $menu ) ) {
						$INCLUDE_MENU = DIR_MODULE.url('module').DIRECTORY_SEPARATOR.'tpl'.DIRECTORY_SEPARATOR.'menu'.$_moduleClass.'.tpl.php';
						include( $INCLUDE_MENU );
					}
					?>

					<div class="<?= url('module') ?> <?= url('action') ?>">
						<? include $this->render( url('module'), url('action') ); ?>
					</div>

					<? if ( isset( $right ) ) : ?>
						<div id="right">
							<? foreach ( $right as $item ) : ?>

							<? endforeach; ?>
						</div>
					<? endif; ?>
				</div>
			</div>

			<div id="footer">
				<? include $this->layout( 'footer' ); ?>
			</div>

			<div class="clear"></div>
		</div>

		<? include $this->layout( 'menuser' ); ?>
	</body>

	<script type="text/javascript">

	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-126426-6']);
	  _gaq.push(['_trackPageview']);

	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();

	</script>
</html>
<? endif; ?>