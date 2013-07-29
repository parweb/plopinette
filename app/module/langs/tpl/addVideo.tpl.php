<form method="post" enctype="multipart/form-data">
	<fieldset>
		<?= form::text( 'video.title', $this->Video->title ) ?>
		<?= form::upload( 'video.image' ) ?>
		<?= form::numeric( 'video.status', $this->Video->status ) ?>
	</fieldset>

	<? if ( url('action') == 'add' ) : ?>
		<?= form::submit( _( 'Ajouter la video' ) ) ?>
	<? else : ?>
		<?= form::hidden( 'video.id', $this->Video->id ) ?>
		<?= form::submit( _( 'Modifier la video' ) ) ?>
	<? endif; ?>
</form>