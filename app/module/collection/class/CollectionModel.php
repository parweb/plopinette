<?php

class CollectionModel extends CoreModel {
	public $fields = array(
		'title' => array(
			'type' => 'text',
			'validate' => array(
				'max' => 255,
				'required' => true
			)
		),
		'user_id' => array(
			'type' => 'numeric',
			'validate' => array()
		)
	);

	public $many = array( 'video' => 'collectioner' );

	public $belong = array( 'user' );

	public $one = array( 'status', 'date' );
	
	public $order = 'date DESC';
	
	public function add ( $array ) {
		if ( !isset( $array['user_id'] ) ) {
			$array['user_id'] = user::id();
		}

		$array['status'] = 1;
		
		$_SESSION['cache']['user'] = true;
	
		return parent::add( $array );
	}
}