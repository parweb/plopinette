<? if ( count( $list ) ) : ?>
	<div id="<?= url('action') ?>_<?= url('module') ?>">
		<table>
			<thead>
				<tr>
					<th>#</th>
					<th>Image</th>
					<th>Titre</th>
					<th>Date</th>
					<th>Statut</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<? foreach ( $list as $item ) : ?>
					<tr>
						<td><?= $item->id ?></td>
						<td><img width="100" src="<?= link::img( 'video', $item->image ) ?>" /></td>
						<td><?= $item->title ?></td>
						<td><?= $item->date ?></td>
						<td><?= $item->status ?></td>
						<td>
							<a href="<?= link::href( 'video', 'edit', array( 'id' => $item->id ) ) ?>">editer</a>
							<a href="<?= link::href( 'video', 'delete', array( 'id' => $item->id ) ) ?>">supprimer</a>
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