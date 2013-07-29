<form method="post" enctype="multipart/form-data">
	<fieldset>
		<?= form::text( 'menu.nom', $this->Menu->nom ) ?>
		<?= form::text( 'menu.sub', $this->Menu->sub ) ?>
		<?= form::text( 'menu.uri', $this->Menu->uri ) ?>
		<?= form::numeric( 'menu.order', $this->Menu->order ) ?>
		<?= form::numeric( 'menu.status', $this->Menu->status ) ?>
	</fieldset>

	<? if ( url('action') == 'add' ) : ?>
		<?= form::submit( _( 'Ajouter le menu' ) ) ?>
	<? else : ?>
		<?= form::hidden( 'menu.id', $this->Menu->id ) ?>
		<?= form::submit( _( 'Modifier le menu' ) ) ?>
	<? endif; ?>
</form>