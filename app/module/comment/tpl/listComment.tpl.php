<? if ( count( $list ) > 0 ) : ?>
	<div id="<?= $URL['action'] ?>_<?= $URL['module'] ?>">
		<table>
			<thead>
				<tr>
					<th>#</th>
					<th>title</th>
					<th>Date</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<? foreach ( $list as $item ) : ?>
					<tr>
						<td><?= $item['id'] ?></td>
						<td><a href="<?= link::href( 'comment', 'view', array( 'id' => $item['id'] ) ) ?>"><?= $item['title'] ?></a></td>
						<td><?= $item['date'] ?></td>
						<td>
							<a href="<?= link::href( 'comment', 'edit', array( 'id' => $item['id'] ) ) ?>">editer</a>
							<a href="<?= link::href( 'comment', 'delete', array( 'id' => $item['id'] ) ) ?>">supprimer</a>
						</td>
					</tr>
				<? endforeach; ?>
			</tbody>
		</table>
	</div>

	<div class="clear"></div>
<? else : ?>
	<p>Aucun résultat <br /><a href="<?= link::href( 'comment', 'add' ) ?>">Ajouter une comment</a></p>
<? endif; ?>