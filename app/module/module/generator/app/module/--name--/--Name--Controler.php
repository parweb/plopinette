<?php

$TPL['breadcrump'][] = _( '--Name--' );

switch ( $URL['action'] ) {
	case 'view':
		$TPL['title'] = $--Name--->--display--;
	break;

	case 'edit':
	case 'add':
		_USER::need( array( 'admin' ) );

		if ( $URL['action'] == 'add' ) {
			$TPL['title'] = _( 'Ajouter un --name--' );
			$TPL['breadcrump'][] = _( 'Ajouter' );
		}
		else {
			$TPL['title'] = _( 'Modifier un --name--' );
			$TPL['breadcrump'][] = _( 'Modifier' );
		}

		if ( count( $_POST ) ) {
			if ( $URL['action'] == 'add' ) {
				$new_id = $--Name--->add( $_POST );
			}
			else {
				$new_id = $URL[':id'];
				$--Name--->save( $new_id, $_POST );
			}

			_URL::redirect( _LINK::href( '--name--', 'list' ) );
		}
	break;

	case 'delete':
		_USER::need( array( 'admin' ) );

		$TPL['title'] = _( 'Supprimer un --name--' );
		$TPL['breadcrump'][] = _( 'Supprimer' );

		$--Name--->delete( $URL[':id'] );

		if ( $URL[':id'] ) {
			_URL::redirect( _LINK::href( '--name--', 'list' ) );
		}
	break;

	default:
		_USER::need( array( 'admin' ) );

		$TPL['title'] = _( 'Liste des --name--s' );
		$TPL['description'] = '';
		$TPL['breadcrump'][] = _( 'Liste' );
	break;
}