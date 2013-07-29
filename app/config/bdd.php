<?php

return array(
	'bdd' => array(
		'active' => true,
		'local' => array(
			'active' => true,
			'type' => 'mysql',
			'host' => 'localhost',
			'port' => '3306',
			'user' => 'root',
			'mdp' => 'root',
			'base' => 'dailymatons'
		),
		'production' => array(
			'type' => 'mysql',
			'host' => 'parweb.fr',
			'port' => '3306',
			'user' => 'root',
			'mdp' => 'projetx29',
			'base' => 'dailymatons'
		)
	)
);