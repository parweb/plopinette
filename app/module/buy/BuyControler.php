<?php

class BuyControler extends AppControler {
	public function before () {
		$this->breadcrump( 'Buy' );
	}

	public function after () {
		//
	}

	public function viewAction () {
		$this->view( 'title', $this->Buy->title );
	}

	public function addAction () {
		user::need( 'member' );

		$this->view( 'title', 'Ajouter une buy' );
		$this->breadcrump( 'Ajouter' );

		$errors = $this->Buy->validate( url(':video_id') );

		if ( !count( $errors ) ) {
			$this->Buy->add( url(':video_id') );

			url::redirect( "video/list/" );
		}
		else {
			$this->view( 'errors', $errors );
		}
	}

	public function editAction () {
		user::need( 'admin' );

		$this->view( 'title', 'Edition d\'une buy' );
		$this->breadcrump( 'Editer' );

		$this->submit();
	}

	public function deleteAction () {
		user::need( 'admin' );

		$this->view( 'title', 'Supprimer une buy' );
		$this->breadcrump( 'Supprimer' );

		$id = $this->delete( url(':id') );

		url::redirect( "buy/list/" );
	}

	public function listAction () {
		//user::need( 'admin' );

		$this->view( 'title', 'Liste des buys' );
		$this->view( 'description', '' );
		$this->breadcrump( 'Liste' );
	}

	public function adminAction () {
		user::need( 'admin' );

		$this->view( 'title', 'Liste des buys' );
		$this->view( 'description', '' );
		$this->breadcrump( 'Liste' );
	}
}