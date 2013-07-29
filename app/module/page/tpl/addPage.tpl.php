<form method="post" enctype="multipart/form-data">
	<fieldset>
		<?= form::text( 'page.title', $this->Page->title ) ?>
		<?= form::textarea( 'page.content', $this->Page->content ) ?>
		<?= form::numeric( 'page.status', $this->Page->status ) ?>
	</fieldset>

	<?= form::hidden( 'page.user_id', user::id() ) ?>

	<? if ( url('action') == 'add' ) : ?>
		<?= form::submit( _( 'Ajouter la page' ) ) ?>
	<? else : ?>
		<?= form::hidden( 'page.id', $this->Page->id ) ?>
		<?= form::submit( _( 'Modifier la page' ) ) ?>
	<? endif; ?>
</form>