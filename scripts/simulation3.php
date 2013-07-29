<?php

include_once __DIR__.'/../core/class/helpers/file.php';
include_once __DIR__.'/../core/class/CoreModel.php';
include_once __DIR__.'/../app/module/allocine/class/AllocineModel.php';

$config = include __DIR__.'/../app/config/bdd.php';
$params = include __DIR__.'/../app/config/params.php';

$bdd = $config['bdd'][$params['site']['env']];

mysql_connect( $bdd['host'], $bdd['user'], $bdd['mdp'] );
mysql_select_db( 'dailymatons_simulation' );


/*

DROP TABLE `bet`, `buy`;

UPDATE  `dailymatons_simulation`.`balance` SET  `montant` =  '0' WHERE  `balance`.`id` = 1;

CREATE TABLE  `dailymatons_simulation`.`buy` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
 `date` DATETIME NOT NULL ,
 `status` INT( 2 ) NOT NULL ,
 `user_id` VARCHAR( 255 ) NOT NULL ,
 `video_id` VARCHAR( 255 ) NOT NULL ,
PRIMARY KEY (  `id` ) ,
UNIQUE KEY  `user_id` (  `user_id` ,  `video_id` )
) ENGINE = INNODB DEFAULT CHARSET = utf8;

INSERT INTO  `dailymatons_simulation`.`buy` SELECT * FROM  `dailymatons_simulation`.`buy_bak`;

CREATE TABLE  `dailymatons_simulation`.`bet` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
 `date` DATETIME NOT NULL ,
 `status` INT( 2 ) NOT NULL ,
 `user_id` VARCHAR( 255 ) NOT NULL ,
 `video_id` VARCHAR( 255 ) NOT NULL ,
PRIMARY KEY (  `id` ) ,
UNIQUE KEY  `user_id` (  `user_id` ,  `video_id` )
) ENGINE = INNODB DEFAULT CHARSET = utf8;

INSERT INTO  `dailymatons_simulation`.`bet` SELECT * FROM  `dailymatons_simulation`.`buy_bak`;

INSERT INTO  `dailymatons_simulation`.`user` SELECT * FROM  `dailymatons`.`user`;

UPDATE  `dailymatons_simulation`.`user` SET  `tickets` = '0';

*/

define( 'VIDEO_PRICE', 1 );
define( 'KEEP', VIDEO_PRICE * 0.4 );
define( 'REFUND', VIDEO_PRICE * 1.2 );

function balance ( $user_id, $type ) {
	if ( $type == 'buy' ) {
		mysql_query( "UPDATE balance SET montant = montant + ".KEEP." WHERE id = 1" );
		mysql_query( "UPDATE balance SET montant = montant + ".( VIDEO_PRICE - KEEP )." WHERE id = 2" );

		mysql_query( "UPDATE user SET tickets = tickets - ".VIDEO_PRICE." WHERE id = $user_id" );

		//echo "+ Achat d'une vidéo à ".VIDEO_PRICE."€ par l'utilisateur : $user_id\n";
	}
	elseif ( $type == 'refund' ) {
		mysql_query( "UPDATE balance SET montant = montant - ".REFUND." WHERE id = 2" );

		mysql_query( "UPDATE user SET tickets = tickets + ".REFUND." WHERE id = $user_id" );

		//echo "- Renboursement de ".VIDEO_REFUND."€ pour l'utilisateur : $user_id\n";
	}
	else {
		exit( "! Aucun type valide spécifié ( buy | refund )\n" );
	}
}

function getBalance () {
	$balance = mysql_query( "SELECT montant FROM balance WHERE id = 2" );
	$balance = mysql_fetch_row( $balance );
	return $balance[0];
}

$buyers = mysql_query( "SELECT * FROM bet WHERE status = 1 ORDER BY date ASC" );
while ( $beter = mysql_fetch_assoc( $buyers ) ) {
	balance( $beter['user_id'], 'buy' );
}

$balance = getBalance();

$buyers = mysql_query( "SELECT * FROM bet WHERE status = 1 ORDER BY RAND()" );
while ( $beter = mysql_fetch_assoc( $buyers ) ) {
	if ( $balance >= REFUND ) {
		balance( $beter['user_id'], 'refund' );

		mysql_query( "UPDATE bet SET status = 0 WHERE id = $beter[id]" );

		$balance -= REFUND;
	}
}

















