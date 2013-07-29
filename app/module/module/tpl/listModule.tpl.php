<? if ( count( $list ) > 0 ) : ?>
	<div id="<?= $URL['action'] ?>_<?= $URL['module'] ?>">
		<table>
			<thead>
				<tr>
					<th>#</th>
					<th>title</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<? foreach ( $list as $item ) : ?>
					<tr>
						<td><?= $item['id'] ?></td>
						<td><a href="<?= link::href( 'module', 'view', array( 'id' => $item['id'] ) ) ?>"><?= $item['title'] ?></a></td>
						<td>
							<a href="<?= link::href( 'module', 'edit', array( 'id' => $item['id'] ) ) ?>">editer</a>
							<a href="<?= link::href( 'module', 'delete', array( 'id' => $item['id'] ) ) ?>">supprimer</a>
						</td>
					</tr>
				<? endforeach; ?>
			</tbody>
		</table>
	</div>

	<div class="clear"></div>
<? else : ?>
	<p>Aucun résultat <br /><a href="<?= link::href( 'module', 'add' ) ?>">Ajouter un module</a></p>
<? endif; ?>