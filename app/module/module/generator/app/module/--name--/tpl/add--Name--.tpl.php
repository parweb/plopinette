<?php

echo '<form method="post" enctype="multipart/form-data">';
	echo '<fieldset>';
		echo _FORM::text( 'title', $--Name--->title );
		echo _FORM::textarea( 'content', $--Name--->content );
	echo '</fieldset>';

	if ( $URL['action'] == 'add' ) {
		echo _FORM::submit( _( 'Ajouter le --name--' ) );
	}
	else {
		echo _FORM::submit( _( 'Modifier le --name--' ) );
	}
echo '</form>';