<form method="post" enctype="multipart/form-data">
	<fieldset>
		<?= form::text( 'comment.title', $Comment->title ) ?>
		<?= form::textarea( 'comment.content', $Comment->content ) ?>
	</fieldset>

	<? if ( url('action') == 'add' ) : ?>
		<?= form::submit( _( 'Ajouter la comment' ) ) ?>
	<? else : ?>
		<?= form::submit( _( 'Modifier la comment' ) ) ?>
	<? endif; ?>
</form>