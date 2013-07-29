<?php

class CollectionControler extends AppControler {
	public function before () {
		$this->breadcrump( 'Collection' );
	}

	public function addAction () {
		user::need( 'member' );

		$this->view( 'title', 'Ajouter une collection' );
		$this->breadcrump( 'Ajouter' );

		$validation = array(
			'title' => array(
				'required' => true,
				'text' => 'simple'
			)
		);
	
		if ( $this->validate( $validation ) ) {
			if ( $this->save() ) {
				user::get('collection');
			}
		}
	}

	public function editAction () {
		user::need( 'member' );

		$this->view( 'title', 'Edition d\'une collection' );
		$this->breadcrump( 'Editer' );

		$this->submit();
	}

	public function listAction () {
		if ( url( ':id' ) > 0 ) {
			url::redirect( 'collection/view/id:'.url( ':id' ).'/' );
		}
		$this->view( 'title', 'Liste des collections' );
		$this->breadcrump( 'List' );

		//$sql = sql::query( "SELECT * FROM menu_collection WHERE status = 1" );
		//$collections = $sql->fetchAll();

		//$this->view( 'list', $collections );
	}

	public function deleteAction () {
		user::need( 'member' );

		$this->view( 'title', 'Supprimer une collection' );
		$this->breadcrump( 'Supprimer' );

		$id = $this->delete( url(':id') );

		url::redirect( "collection/list/" );
	}

	public function viewAction () {
		//user::need( 'admin' );

		$this->view( 'title', 'Collection : '.collection::title( url(':id') ) );
		$this->view( 'description', '' );
		$this->breadcrump( 'Liste' );

		$options = url::url2params();

		$count = video::collection( url(':id'), 'count' );
		$pages = ceil( $count / $options['limit'] );

		$this->view( 'count', $count );
		$this->view( 'pages', $pages );

		$this->view( 'list', video::collection( url(':id') ) );
	}

	public function adminAction () {
		user::need( 'admin' );

		$this->view( 'title', 'Liste des collections' );
		$this->view( 'description', '' );
		$this->breadcrump( 'Liste' );
	}
}