<?php

class GenreControler extends AppControler {
	public function before () {
		$this->breadcrump( 'Genre' );
	}

	public function save () {
		if ( isset( $_FILES['genre'] ) ) {
			$name = $_FILES['genre']['name']['image'];
			$tmp_name = $_FILES['genre']['tmp_name']['image'];

			$extention = end( explode( '.', $name ) );

			$new_file_dir = $this->upload;
			$new_file_name = time().'-'.rand( 10000, 99999 ).'-'._::clean( $_POST['genre']['title'] ).'.'.$extention;

			$new_file = $new_file_dir.$new_file_name;

			if ( move_uploaded_file( $tmp_name, $new_file ) ) {
				$_POST['genre']['image'] = $new_file_name;

				return parent::save();
			}
		}
	}

	public function addAction () {
		user::need( 'admin' );

		$this->view( 'title', 'Ajouter une genre' );
		$this->breadcrump( 'Ajouter' );

		$this->submit();
	}

	public function editAction () {
		user::need( 'admin' );

		$this->view( 'title', 'Edition d\'une genre' );
		$this->breadcrump( 'Editer' );

		$this->submit();
	}

	public function listAction () {
		if ( url( ':id' ) > 0 ) {
			url::redirect( 'genre/view/id:'.url( ':id' ).'/' );
		}
		$this->view( 'title', 'Liste des genres' );
		$this->breadcrump( 'List' );

		$sql = sql::query( "SELECT * FROM menu_genre WHERE status = 1" );
		$genres = $sql->fetchAll();

		$this->view( 'list', $genres );
	}

	public function deleteAction () {
		user::need( 'admin' );

		$this->view( 'title', 'Supprimer une genre' );
		$this->breadcrump( 'Supprimer' );

		$id = $this->delete( url(':id') );

		url::redirect( "genre/list/" );
	}

	public function viewAction () {
		$this->view( 'title', 'Genre : '.genre::title( url(':id') ) );
		$this->view( 'description', '' );
		$this->breadcrump( 'Liste' );

		$options = url::url2params();

		$count = video::genre( url(':id'), 'count' );
		$pages = ceil( $count / $options['limit'] );

		$this->view( 'count', $count );
		$this->view( 'pages', $pages );

		$this->view( 'list', video::genre( url(':id') ) );
	}

	public function adminAction () {
		user::need( 'admin' );

		$this->view( 'title', 'Liste des genres' );
		$this->view( 'description', '' );
		$this->breadcrump( 'Liste' );
	}
}