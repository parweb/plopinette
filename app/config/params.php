<?php

return array(
	'view' => array(
		'template' => 'default',
		'layout' => 'layout'
	),
	'security' => array(
		'salt' => 'dgkzehgisdbvèsdç!bvèsdçbvsiuvhsdvsdèvds(v!§sd!èvèsdv!èsdè(v!s'
	),
	'site' => array(
		'auth' => true,
		'cache' => 54, // soit durée de vie en minutes ou false
		'env' => HOST == 'localhost' ? 'local' : 'production',
	),
	'mail' => array(
		'contact' => array(
			'name' => 'Dailymatons.com',
			'email' => 'contact@dailymatons.com'
		),
		'template' => 'default'
	),
	'subscription' => array(
		'prices' => array(
			1 => 2,
			2 => 4,
			3 => 6
		),
		'day' => 7,
		'free' => 2 //1 film gratuit toutes les x semaine(s).
	),
	'paypal' => array(
		'mail' => 'parweb1-facilitator@free.fr',
		'USER' => 'parweb1-facilitator_api1.free.fr',
		'PWD' => '1375065783',
		'SIGNATURE' => 'A3cJK5AacpX0lp9pwB32Xwb6T.Y6ADADMekjsLbXx74U64ry1ANFedCc',
		'sandbox' => 'sandbox.'
	)
);