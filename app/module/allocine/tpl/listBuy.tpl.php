<? if ( count( $list ) > 0 ) : ?>
	<div id="<?= $URL['action'] ?>_<?= $URL['module'] ?>">
		<table>
			<thead>
				<tr>
					<th>#</th>
					<th>User</th>
					<th>Video</th>
					<th>Date</th>
					<th>Status</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<? foreach ( $list as $item ) : ?>
					<tr>
						<td><?= $item->id ?></td>
						<td><?= $item->user['login'] ?></td>
						<td><?= $item->video['title'] ?></td>
						<td><?= $item->date ?></td>
						<td><?= $item->status ?></td>
						<td>
							<a href="<?= link::href( 'buy', 'edit', array( 'id' => $item->id ) ) ?>">editer</a>
							<a href="<?= link::href( 'buy', 'delete', array( 'id' => $item->id ) ) ?>">supprimer</a>
						</td>
					</tr>
				<? endforeach; ?>
			</tbody>
		</table>
	</div>

	<div class="clear"></div>
<? else : ?>
	<p>Aucun résultat</p>
<? endif; ?>