<? if ( count( $list ) > 0 ) : ?>
	<div id="<?= $URL['action'] ?>_<?= $URL['module'] ?>">
		<table>
			<thead>
				<tr>
					<th>#</th>
					<th>Nom</th>
					<th>Alias</th>
					<th>Url</th>
					<th>Position</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<? foreach ( $list as $item ) : ?>
					<tr>
						<td><?= $item->id ?></td>
						<td><?= $item->nom ?></td>
						<td><?= $item->sub ?></td>
						<td><?= $item->uri ?></td>
						<td><?= $item->order ?></td>
						<td>
							<a href="<?= link::href( 'menu', 'edit', array( 'id' => $item->id ) ) ?>">editer</a>
							<a href="<?= link::href( 'menu', 'delete', array( 'id' => $item->id ) ) ?>">supprimer</a>
						</td>
					</tr>
				<? endforeach; ?>
			</tbody>
		</table>
	</div>

	<div class="clear"></div>
<? else : ?>
	<p>Aucun résultat <br /><a href="<?= link::href( 'menu', 'add' ) ?>">Ajouter un menu</a></p>
<? endif; ?>