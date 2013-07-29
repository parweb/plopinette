<?php

class SponsorshipModel extends CoreModel {
	public $fields = array(
		'godfather_id' => array(
			'type' => 'numeric',
			'validate' => array()
		),
		'slave_id' => array(
			'type' => 'numeric',
			'validate' => array()
		)
	);

	public $one = array( 'date', 'status' );
}