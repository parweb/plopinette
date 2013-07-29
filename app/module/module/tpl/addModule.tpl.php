<form method="post" enctype="multipart/form-data">
	<fieldset>
		<?= form::text( 'module.title', $Module->title ) ?>
	</fieldset>

	<? if ( url('action') == 'add' ) : ?>
		<?= form::submit( _( 'Ajouter le module' ) ) ?>
	<? else : ?>
		<?= form::submit( _( 'Modifier le module' ) ) ?>
	<? endif; ?>
</form>