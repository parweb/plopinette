<? if ( count( $list ) ) : ?>
	<div id="<?= url('action') ?>_<?= url('module') ?>">
		<table>
			<thead>
				<tr>
					<th>#</th>
					<th>Titre</th>
					<th>Utilisateur</th>
					<th>Date</th>
					<th>Statut</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<? foreach ( $list as $item ) : ?>
					<tr>
						<td><?= $item->id ?></td>
						<td><a href="<?= link::href( 'page', 'view', array( 'id' => $item->id ) ) ?>"><?= $item->title ?></a></td>
						<td><?= $item->user['login'] ?></td>
						<td><?= $item->date ?></td>
						<td><?= $item->status ?></td>
						<td>
							<a href="<?= link::href( 'page', 'edit', array( 'id' => $item->id ) ) ?>">editer</a>
							<a href="<?= link::href( 'page', 'delete', array( 'id' => $item->id ) ) ?>">supprimer</a>
						</td>
					</tr>
				<? endforeach; ?>
			</tbody>
		</table>
	</div>

	<div class="clear"></div>
<? else : ?>
	<p>Aucun résultat <br /><a href="<?= link::href( 'page', 'add' ) ?>">Ajouter une page</a></p>
<? endif; ?>