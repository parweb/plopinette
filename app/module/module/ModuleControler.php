<?php

class ModuleControler extends AppControler {
	protected function before () {
		user::need( 'admin' );

		$this->breadcrump( 'Module' );
	}

	protected function after () {
		//
	}

	protected function submit () {
		if ( $this->validate() ) {
			$id = $this->save();

			if ( $id ) {
				url::redirect( "module/list/$id/" );
			}
		}
	}

	public function viewAction () {
		$this->view( 'title', $this->title );
	}

	public function addAction () {
		$this->view( 'title', 'Ajouter un module' );
		$this->breadcrump( 'Ajouter' );

		$this->submit();
	}

	public function editAction () {
		$this->view( 'title', 'Edition d\'un module' );
		$this->breadcrump( 'Editer' );

		$this->submit();
	}

	public function deleteAction () {
		$this->view( 'title', 'Supprimer un module' );
		$this->breadcrump( 'Supprimer' );

		$id = $this->delete();

		if ( $id ) {
			url::redirect( "module/list/" );
		}
	}

	public function indexAction () {
		$this->view( 'title', 'Liste des modules' );
		$this->view( 'description', '' );
		$this->breadcrump( 'Liste' );
	}
}