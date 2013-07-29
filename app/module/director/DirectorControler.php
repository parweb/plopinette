<?php

class DirectorControler extends AppControler {
	public function before () {
		$this->breadcrump( 'Director' );
	}

	public function after () {
		//
	}

	public function viewAction () {
		$this->view( 'title', $this->Director->firstname.' '.$this->lastname );
	}

	public function listAction () {
		//user::need( 'admin' );

		$this->view( 'title', 'Réalisateur : '.director::title( url(':id') ) );
		$this->view( 'description', '' );
		$this->breadcrump( 'Liste' );

		$options = url::url2params();

		$count = video::director( url(':id'), 'count' );
		$pages = ceil( $count / $options['limit'] );

		$this->view( 'count', $count );
		$this->view( 'pages', $pages );

		$this->view( 'list', video::director( url(':id') ) );
	}

	public function adminAction () {
		user::need( 'admin' );

		$this->view( 'title', 'Liste des directors' );
		$this->view( 'description', '' );
		$this->breadcrump( 'Liste' );
	}
}