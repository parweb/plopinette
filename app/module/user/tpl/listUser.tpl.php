<? if ( count( $list ) > 0 ) : ?>
	<div id="<?= $URL['action'] ?>_<?= $URL['module'] ?>">
		<table>
			<thead>
				<tr>
					<th>#</th>
					<th>Login</th>
					<th>Email</th>
					<th>Status</th>
					<th>Date</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<? foreach ( $list as $item ) : ?>
					<tr>
						<td><?= $item->id ?></td>
						<td><?= $item->login ?></td>
						<td><?= $item->email ?></td>
						<td><?= $item->status ?></td>
						<td><?= $item->date ?></td>
						<td>
							<a href="<?= link::href( "user/edit/id:$item->id" ) ?>">editer</a>
							<a href="<?= link::href( "user/delete/id:$item->id" ) ?>">supprimer</a>
						</td>
					</tr>
				<? endforeach; ?>
			</tbody>
		</table>
	</div>

	<div class="clear"></div>
<? else : ?>
	<p>Aucun résultat <br /><a href="<?= link::href( 'user/add' ) ?>">Ajouter un utilisateur</a></p>
<? endif; ?>