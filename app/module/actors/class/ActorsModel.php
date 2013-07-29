<?php

class ActorsModel extends CoreModel {
	public $fields = array();

	public $many = array();

	public $belong = array( 'artist', 'video' );

	public $one = array();

	public function listes ( $appli = array(), $sortie = 'recursif', $field = '' ) {
		if ( isset( $appli['where'] ) && !empty( $appli['where'] ) && is_string( $appli['where'] ) ) {
			$where = $appli['where'];

			$appli['where'] = array();
			$appli['where'][] = $where;
		}

		$appli['orderby'] = 'actors.id ASC';

		return parent::listes( $appli, $sortie, $field );
	}
}