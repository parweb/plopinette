<? if ( count( $list ) ) : ?>
	<div id="<?= url('action') ?>_<?= url('module') ?>">
		<table>
			<thead>
				<tr>
					<th>#</th>
					<th>Image</th>
					<th>Titre</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<? foreach ( $list as $item ) : ?>
					<tr>
						<td><?= $item->id ?></td>
						<td><img height="100" src="<?= link::img( 'video', $item->image ) ?>" /></td>
						<td><?= $item->title ?></td>
						<td>
							<a href="<?= link::href( 'buy', 'add', array( 'video_id' => $item->id ) ) ?>">Acheter ( <?= count( $item->buy ) ?> )</a>
							<a href="<?= link::href( 'bet', 'add', array( 'video_id' => $item->id ) ) ?>">Parier ( <?= count( $item->bet ) ?> )</a>
						</td>
					</tr>
				<? endforeach; ?>
			</tbody>
		</table>
	</div>

	<div class="clear"></div>
<? else : ?>
	<p>Aucun résultat <br /><a href="<?= link::href( 'video', 'add' ) ?>">Ajouter une video</a></p>
<? endif; ?>