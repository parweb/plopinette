<? include $this->layout( 'pagination' ); ?>

<? if ( count( $list ) ) : ?>
	<div id="list_genre">
		<? foreach ( $list as $item ) : ?>
			<div class="genre">
				<a data-title="<?= $item['title'] ?>" href="<?= link::href( "genre/view/id:$item[id]" ) ?>">
					<div><?= $item['title'] ?></div><small><?= $item['count'] ?> films</small>
				</a>
			</div>
		<? endforeach; ?>

		<div class="clear"></div>
	</div>

	<div class="clear"></div>

	<? include $this->layout( 'pagination' ); ?>
<? else : ?>
	<p>Aucun résultat <br /><a href="<?= link::href( 'genre', 'add' ) ?>">Ajouter un genre</a></p>
<? endif; ?>