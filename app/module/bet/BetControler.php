<?php

class BetControler extends AppControler {
	public function before () {
		$this->breadcrump( 'Bet' );
	}

	public function after () {
		//
	}

	public function viewAction () {
		$this->view( 'title', $this->Bet->title );
	}

	public function addAction () {
		user::need( 'member' );

		$this->view( 'title', 'Ajouter une bet' );
		$this->breadcrump( 'Ajouter' );

		$errors = $this->Bet->validate( url(':video_id') );

		if ( !count( $errors ) ) {
			$this->Bet->add( url(':video_id') );

			url::redirect( "video/list/" );
		}
		else {
			$this->view( 'errors', $errors );
		}
	}

	public function editAction () {
		user::need( 'admin' );

		$this->view( 'title', 'Edition d\'une bet' );
		$this->breadcrump( 'Editer' );

		$this->submit();
	}

	public function deleteAction () {
		user::need( 'admin' );

		$this->view( 'title', 'Supprimer une bet' );
		$this->breadcrump( 'Supprimer' );

		$id = $this->delete( url(':id') );

		url::redirect( "bet/list/" );
	}

	public function listAction () {
		//user::need( 'admin' );

		$this->view( 'title', 'Liste des bets' );
		$this->view( 'description', '' );
		$this->breadcrump( 'Liste' );
	}

	public function adminAction () {
		user::need( 'admin' );

		$this->view( 'title', 'Liste des bets' );
		$this->view( 'description', '' );
		$this->breadcrump( 'Liste' );
	}
}