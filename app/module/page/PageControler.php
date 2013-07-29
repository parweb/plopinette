<?php

class PageControler extends AppControler {
	public function before () {
		$this->breadcrump( 'Page' );
	}

	public function after () {
		//
	}

	public function viewAction () {
		$this->view( 'title', $this->Page->title );
	}

	public function addAction () {
		user::need( 'admin' );

		$this->view( 'title', 'Ajouter une page' );
		$this->breadcrump( 'Ajouter' );

		$this->submit();
	}

	public function editAction () {
		user::need( 'admin' );

		$this->view( 'title', 'Edition d\'une page' );
		$this->breadcrump( 'Editer' );

		$this->submit();
	}

	public function deleteAction () {
		user::need( 'admin' );

		$this->view( 'title', 'Supprimer d\'une page' );
		$this->breadcrump( 'Supprimer' );

		$id = $this->delete( url(':id') );

		url::redirect( "page/list/" );
	}

	public function listAction () {
		//user::need( 'admin' );

		$this->view( 'title', 'Liste des pages' );
		$this->view( 'description', '' );
		$this->breadcrump( 'Liste' );
	}

	public function feedbackAction () {
		//user::need( 'admin' );

		$this->view( 'title', 'Foire aux questions' );
		$this->view( 'description', 'Foire aux questions' );
		$this->breadcrump( 'Feedback' );
	}
}

/*
$TPL['breadcrump'][] = _( 'Page' );

switch ( $URL['action'] ) {
	case 'view':
		$TPL['title'] = $Page->title;
	break;

	case 'edit':
	case 'add':
		user::need( array( 'admin' ) );

		if ( $URL['action'] == 'add' ) {
			$TPL['title'] = _( 'Ajouter une page' );
			$TPL['breadcrump'][] = _( 'Ajouter' );
		}
		else {
			$TPL['title'] = _( 'Modifier une page' );
			$TPL['breadcrump'][] = _( 'Modifier' );
		}

		if ( count( $_POST ) ) {
			if ( $URL['action'] == 'add' ) {
				$new_id = $Page->add( $_POST );
			}
			else {
				$new_id = $URL[':id'];
				$Page->save( $new_id, $_POST );
			}

			url::redirect( link::href( 'page', 'list' ) );
		}
	break;

	case 'delete':
		user::need( array( 'admin' ) );

		$TPL['title'] = _( 'Supprimer une page' );
		$TPL['breadcrump'][] = _( 'Supprimer' );

		$Page->delete( $URL[':id'] );

		if ( $URL[':id'] ) {
			url::redirect( link::href( 'page', 'list' ) );
		}
	break;

	default:
		user::need( array( 'admin' ) );

		$TPL['title'] = _( 'Liste des pages' );
		$TPL['description'] = '';
		$TPL['breadcrump'][] = _( 'Liste' );
	break;
}
*/