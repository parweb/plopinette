<?php

class LangsModel extends CoreModel {
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

	public $many = array( 'video' );

	public $belong = array( 'video' );

	public $one = array( 'status' );
}