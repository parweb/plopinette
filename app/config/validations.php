<?php

return array(
	'validations' => array(
		'id' => array(
			'max' => 11
		),
		'login' => array(
			'required' => true,
			'unique' => true,
			'max' => 255,
			'text' => 'simple'
		),
		'email' => array(
			'required' => true,
			'unique' => true,
			'max' => 255,
			'text' => 'email'
		),
		'pass' => array(
			'required' => true,
			'max' => 255,
			'min' => 6,
			'text' => 'advanced'
		),
		'facebook_id' => array()
	)
);