<?php

class DateControler extends AppControler {
	public function before () {
		$this->breadcrump( 'Date' );
	}

	public function after () {
		//
	}

	public function listAction () {
		//user::need( 'admin' );

		$this->view( 'title', 'Année : '.url(':d') );
		$this->view( 'description', '' );
		$this->breadcrump( 'Liste' );

		$options = url::url2params();

		$count = video::date( url(':d'), 'count' );
		$pages = ceil( $count / $options['limit'] );

		$this->view( 'count', $count );
		$this->view( 'pages', $pages );

		$this->view( 'list', video::date( url(':d') ) );
	}
}