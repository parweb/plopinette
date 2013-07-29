<? if ( count( $list ) > 0 ) : ?>
	<div id="<?= $URL['action'] ?>_<?= $URL['module'] ?>">
		<table>
			<thead>
				<tr>
					<th>#</th>
					<th>User</th>
					<th>Video</th>
					<th>Status</th>
					<th>Date</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<? foreach ( $list as $item ) : ?>
					<tr class="<?= $item->status == 1 ? 'bad' : 'good' ?>">
						<td><?= $item->id ?></td>
						<td><?= $item->user['login'] ?></td>
						<td><a href="<?= link::href( 'video/view/id:'.$item->video['id'] ) ?>"><?= $item->video['title'] ?></a></td>
						<td><?= $item->status ?></td>
						<td><?= $item->date ?></td>
					</tr>
				<? endforeach; ?>
			</tbody>
		</table>
	</div>

	<div class="clear"></div>
<? else : ?>
	<p>Aucune alerte</p>
<? endif; ?>