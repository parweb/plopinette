<?php

class CommentModel extends CoreModel {
	public $fields = array(
		'title' => array(
			'type' => 'text',
			'validate' => array(
				'max' => 255,
				'required' => true
			)
		),
		'content' => array(
			'type' => 'textarea',
			'validate' => array(
				'required' => true
			)
		)
	);

	//public $many = array( 'comment' );

	public $belong = array( 'user', 'video' );

	public $one = array( 'date', 'status', 'order' );
}