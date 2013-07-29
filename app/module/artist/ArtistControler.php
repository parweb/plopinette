<?php

class ArtistControler extends AppControler {
	public function before () {
		$this->breadcrump( 'Artist' );
	}

	public function after () {
		//
	}

	public function viewAction () {
		$this->view( 'title', $this->Artist->firstname.' '.$this->Artist->lastname );
	}

	public function save () {
		if ( isset( $_FILES['artist'] ) ) {
			$name = $_FILES['artist']['name']['image'];
			$tmp_name = $_FILES['artist']['tmp_name']['image'];

			$extention = end( explode( '.', $name ) );

			$new_file_dir = $this->upload;
			$new_file_name = time().'-'.rand( 10000, 99999 ).'-'._::clean( $_POST['artist']['title'] ).'.'.$extention;

			$new_file = $new_file_dir.$new_file_name;

			if ( move_uploaded_file( $tmp_name, $new_file ) ) {
				$_POST['artist']['image'] = $new_file_name;

				return parent::save();
			}
		}
	}

	public function addAction () {
		user::need( 'admin' );

		$this->view( 'title', 'Ajouter une artist' );
		$this->breadcrump( 'Ajouter' );

		$this->submit();
	}

	public function editAction () {
		user::need( 'admin' );

		$this->view( 'title', 'Edition d\'une artist' );
		$this->breadcrump( 'Editer' );

		$this->submit();
	}

	public function deleteAction () {
		user::need( 'admin' );

		$this->view( 'title', 'Supprimer une artist' );
		$this->breadcrump( 'Supprimer' );

		$id = $this->delete( url(':id') );

		url::redirect( "artist/list/" );
	}

	public function listAction () {
		//user::need( 'admin' );

		$this->view( 'title', 'Artiste : '.artist::title( url(':id') ) );
		$this->view( 'description', '' );
		$this->breadcrump( 'Liste' );

		$options = url::url2params();

		$count = video::artist( url(':id'), 'count' );
		$pages = ceil( $count / $options['limit'] );

		$this->view( 'count', $count );
		$this->view( 'pages', $pages );

		$this->view( 'list', video::artist( url(':id') ) );
	}
	public function adminAction () {
		user::need( 'admin' );

		$this->view( 'title', 'Liste des artists' );
		$this->view( 'description', '' );
		$this->breadcrump( 'Liste' );
	}
}