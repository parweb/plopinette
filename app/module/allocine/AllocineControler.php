<?php

class AllocineControler extends AppControler {
	public function before () {
		$this->breadcrump( 'Allocine' );
	}

	public function after () {
		//
	}

	public function viewAction () {
		$this->view( 'title', $this->Allocine->title );
	}

	public function addAction () {
		user::need( 'member' );

		$this->view( 'title', 'Ajouter une allocine' );
		$this->breadcrump( 'Ajouter' );

		$errors = $this->Allocine->validate( url(':video_id') );

		if ( !count( $errors ) ) {
			$this->Allocine->add( url(':video_id') );

			url::redirect( "video/list/" );
		}
		else {
			$this->view( 'errors', $errors );
		}
	}

	public function editAction () {
		user::need( 'admin' );

		$this->view( 'title', 'Edition d\'une allocine' );
		$this->breadcrump( 'Editer' );

		$this->submit();
	}

	public function deleteAction () {
		user::need( 'admin' );

		$this->view( 'title', 'Supprimer une allocine' );
		$this->breadcrump( 'Supprimer' );

		$id = $this->delete( url(':id') );

		url::redirect( "allocine/list/" );
	}

	public function listAction () {
		//user::need( 'admin' );

		$this->view( 'title', 'Liste des allocines' );
		$this->view( 'description', '' );
		$this->breadcrump( 'Liste' );

		$Allocine = new AllocineModel( url(':id') );
		$Allocine->getInfos();
		echo '<pre>'.__FILE__.' ( '.__LINE__.' ) ';
			print_r( $Allocine );
		echo '</pre>';exit;
	}

	public function adminAction () {
		user::need( 'admin' );

		$this->view( 'title', 'Liste des allocines' );
		$this->view( 'description', '' );
		$this->breadcrump( 'Liste' );
	}
}