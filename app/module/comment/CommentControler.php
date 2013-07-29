<?php

class CommentControler extends AppControler {
	public function before () {
		//user::need( array( 'admin' ) );

		$this->breadcrump( 'Comment' );
	}

	public function after () {
		//
	}

	protected function submit () {
		if ( $this->validate() ) {
			$id = $this->save();

			if ( $id ) {
				url::redirect( "comment/list/$id/" );
			}
		}
	}

	public function viewAction () {
		$this->view( 'title', $this->title );
	}

	public function addAction () {
		$this->view( 'title', 'Ajouter une comment' );
		$this->breadcrump( 'Ajouter' );

		$this->submit();
	}

	public function editAction () {
		$this->view( 'title', 'Edition d\'une comment' );
		$this->breadcrump( 'Editer' );

		$this->submit();
	}

	public function deleteAction () {
		$this->view( 'title', 'Supprimer d\'une comment' );
		$this->breadcrump( 'Supprimer' );

		$id = $this->delete();

		if ( $id ) {
			url::redirect( "comment/list/" );
		}
	}

	public function listAction () {
		$this->view( 'title', 'Liste des comments' );
		$this->view( 'description', '' );
		$this->breadcrump( 'Liste' );
	}
}