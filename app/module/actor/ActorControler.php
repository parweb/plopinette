<?php

class ActorControler extends AppControler {
	public function before () {
		$this->breadcrump( 'Actor' );
	}

	public function after () {
		//
	}

	public function viewAction () {
		$this->view( 'title', $this->Actor->firstname.' '.$this->lastname );
	}

	public function listAction () {
		//user::need( 'admin' );

		$this->view( 'title', 'Acteur : '.actor::title( url(':id') ) );
		$this->view( 'description', '' );
		$this->breadcrump( 'Liste' );

		$options = url::url2params();

		$count = video::actor( url(':id'), 'count' );
		$pages = ceil( $count / $options['limit'] );

		$this->view( 'count', $count );
		$this->view( 'pages', $pages );

		$this->view( 'list', video::actor( url(':id') ) );
	}

	public function adminAction () {
		user::need( 'admin' );

		$this->view( 'title', 'Liste des actors' );
		$this->view( 'description', '' );
		$this->breadcrump( 'Liste' );
	}
}