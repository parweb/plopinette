<?php

class VideoModel extends CoreModel {
	public $fields = array(
		'title' => array(
			'type' => 'text',
			'validate' => array(
				'max' => 255,
				'required' => true
			)
		),
		'title_o' => array(
			'type' => 'text',
			'validate' => array(
				'max' => 255,
				'required' => true
			)
		),
		'allocine_id' => array(
			'type' => 'numeric',
			'validate' => array()
		),
		'tmdb_id' => array(
			'type' => 'numeric',
			'validate' => array()
		),
		'image' => array(
			'type' => 'file',
			'validate' => array()
		),
		'duration' => array(
			'type' => 'numeric',
			'validate' => array(
				'required' => true
			)
		),
		'view' => array(
			'type' => 'numeric',
			'validate' => array(
				'required' => true
			)
		),
		'abstract' => array(
			'type' => 'text'
		),
		'release' => array(
			'type' => 'date',
			'validate' => array(
				'required' => true
			)
		),
		'trailer' => array(
			'type' => 'text',
			'validate' => array(
				'max' => 255
			)
		),
		'search' => array(
			'type' => 'text'
		),
		'feature' => array(
			'type' => 'numeric',
			'validate' => array()
		)
	);

	public $order = 'release DESC';

	public $many = array( 'actors', 'directors', 'genres', 'langs' );

	public $belong = array();

	public $one = array( 'date', 'status' );

	public function listes ( $appli = array(), $sortie = 'recursif', $field = '' ) {
		if ( isset( $appli['where'] ) && !empty( $appli['where'] ) && is_string( $appli['where'] ) ) {
			$where = $appli['where'];

			$appli['where'] = array();
			$appli['where'][] = $where;
		}

		$statut = isset( $_GET['id'] ) ? $_GET['id'] : 1;

		$appli['where'][] = 'video.status = '.$statut;
		if ( !isset( $appli['orderby'] ) ) $appli['orderby'] = 'video.release DESC';

		return parent::listes( $appli, $sortie, $field );
	}
}