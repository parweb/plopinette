<?php

class CollectionerModel extends CoreModel {
	public $fields = array();

	public $many = array();

	public $belong = array( 'collection', 'video' );

	public $one = array();
	
	public function add ( $array ) {
		$array['status'] = 1;

		return parent::add( $array );
	}
}