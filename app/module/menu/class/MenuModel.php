<?php

class MenuModel extends CoreModel {
	public $fields = array(
		'nom' => array(
			'type' => 'text',
			'validate' => array(
				'max' => 255,
				'required' => true
			)
		),
		'sub' => array(
			'type' => 'text',
			'validate' => array(
				'max' => 255,
			)
		),
		'uri' => array(
			'type' => 'text',
			'validate' => array(
				'max' => 255,
			)
		)
	);

	public $belong = array();

	public $one = array( 'date', 'status', 'order' );

	public $display = 'nom';
}