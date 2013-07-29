<?php

class GenreModel extends CoreModel {
	public $fields = array(
		'title' => array(
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

	public $many = array( 'genres' );

	public $belong = array();

	public $one = array( 'status' );
}