<?php

class LangControler extends AppControler {
	public function before () {
		$this->breadcrump( 'Lang' );
	}

	public function after () {
		//
	}

	public function viewAction () {
		$this->view( 'title', $this->Lang->title );
	}

	public function save () {
		if ( isset( $_FILES['lang'] ) ) {
			$name = $_FILES['lang']['name']['image'];
			$tmp_name = $_FILES['lang']['tmp_name']['image'];

			$extention = end( explode( '.', $name ) );

			$new_file_dir = $this->upload;
			$new_file_name = time().'-'.rand( 10000, 99999 ).'-'._::clean( $_POST['lang']['title'] ).'.'.$extention;

			$new_file = $new_file_dir.$new_file_name;

			if ( move_uploaded_file( $tmp_name, $new_file ) ) {
				$_POST['lang']['image'] = $new_file_name;

				return parent::save();
			}
		}
	}

	public function addAction () {
		user::need( 'admin' );

		$this->view( 'title', 'Ajouter une lang' );
		$this->breadcrump( 'Ajouter' );

		$this->submit();
	}

	public function editAction () {
		user::need( 'admin' );

		$this->view( 'title', 'Edition d\'une lang' );
		$this->breadcrump( 'Editer' );

		$this->submit();
	}

	public function deleteAction () {
		user::need( 'admin' );

		$this->view( 'title', 'Supprimer une lang' );
		$this->breadcrump( 'Supprimer' );

		$id = $this->delete( url(':id') );

		url::redirect( "lang/list/" );
	}

	public function listAction () {
		//user::need( 'admin' );

		$this->view( 'title', 'Langue : '.lang::title( url(':id') ) );
		$this->view( 'description', '' );
		$this->breadcrump( 'Liste' );

		$options = url::url2params();

		$count = video::lang( url(':id'), 'count' );
		$pages = ceil( $count / $options['limit'] );

		$this->view( 'count', $count );
		$this->view( 'pages', $pages );

		$this->view( 'list', video::lang( url(':id') ) );
	}

	public function adminAction () {
		user::need( 'admin' );

		$this->view( 'title', 'Liste des langs' );
		$this->view( 'description', '' );
		$this->breadcrump( 'Liste' );
	}
}