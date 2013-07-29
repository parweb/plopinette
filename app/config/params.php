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
		'free' => 2 //1 film gratuit toutes les x semaine(s).
	),
	'paypal' => array(
		'mail' => 'seller_1360811542_biz@free.fr',
		'USER' => 'seller_1360811542_biz_api1.free.fr',
		'PWD' => '1360811567',
		'SIGNATURE' => 'AFcWxV21C7fd0v3bYYYRCpSSRl31A39kCBAzSwZM4GZm-TiLgvW1Agrb',
		'sandbox' => 'sandbox.'
	)
);