<?php

class LangModel extends CoreModel {
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

	public $many = array( 'comment', 'video' );

	public $belong = array( 'comment', 'video' );

	public $one = array( 'status' );
}