<?php

class StatControler extends AppControler {
	public function before () {
		user::need( 'admin' );

		$this->breadcrump( 'Statistique' );
	}

	public function listAction () {
		$this->view( 'title', 'Liste des Statistiques' );
		$this->view( 'description', '' );
		$this->breadcrump( 'Toutes les statistiques' );

		$type = url( ':type', 'week' );
		$table = url( ':table', 'user' );

		$users = sql::query( "SELECT * FROM `stats_{$table}_{$type}` ORDER BY date ASC" );
		$users = $users->fetchAll();

		$count = sql::query( "SELECT max(count) FROM `stats_{$table}_{$type}`" ); $count = $count->fetch();
		$total = sql::query( "SELECT sum(count) FROM `stats_{$table}_{$type}`" ); $total = $total->fetch();

		$max = array(
			'user' => $total[0],
			'count' => $count[0],
			'period' => count( $users )
		);

		$this->view( 'users', $users );
		$this->view( 'max', $max );
		$this->view( 'graph', array( 'h' => 500 ) );
	}
}