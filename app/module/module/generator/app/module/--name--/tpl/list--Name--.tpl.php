<?php

if ( count( $list ) > 0 ) {
	echo '<div id="'.$URL['action'].'_'.$URL['module'].'">';
		echo '<table>
			<thead>
				<tr>
					<th>#</th>
					<th>title</th>
					<th>Date</th>
					<th></th>
				</tr>
			</thead>
			<tbody>';
				foreach ( $list as $item ) {
					echo '<tr>';
						echo '<td>'.$item['id'].'</td>';
						echo '<td><a href="'._LINK::href( '--name--', 'view', array( 'id' => $item['id'] ) ).'">'.$item['title'].'</a></td>';
						echo '<td>'.$item['date'].'</td>';
						echo '<td>
							<a href="'._LINK::href( '--name--', 'edit', array( 'id' => $item['id'] ) ).'">editer</a>
							<a href="'._LINK::href( '--name--', 'delete', array( 'id' => $item['id'] ) ).'">supprimer</a>
						</td>';
					echo '</tr>';
				}
			echo '</tbody>
		</table>';
	echo '</div>

	<div class="clear"></div>';
}
else {
	echo '<p>Aucun résultat <br /><a href="'._LINK::href( '--name--', 'add' ).'">Ajouter un --name--</a></p>';
}