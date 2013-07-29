<?php

class ArtistModel extends CoreModel {
	public $fields = array(
		'firstname' => array(
			'type' => 'text',
			'validate' => array(
				'max' => 255,
				'required' => true
			)
		),
		'lastname' => array(
			'type' => 'text',
			'validate' => array(
				'max' => 255,
				'required' => true
			)
		),
		'image' => array(
			'type' => 'file',
			'validate' => array()
		)
	);

	public $many = array( 'video' );

	public $belong = array( 'director', 'actor' );

	public $one = array( 'status' );
}