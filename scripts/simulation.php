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

define( 'VIDEO_PRICE', 5 );
define( 'RETURN_RATIO', 0.75 );

define( 'VIDEO_REFUND', RETURN_RATIO * VIDEO_PRICE );

function balance ( $user_id, $type ) {
	if ( $type == 'buy' ) {
		mysql_query( "UPDATE balance SET montant = montant + ".VIDEO_PRICE." WHERE id = 1" );
		mysql_query( "UPDATE user SET tickets = tickets - ".VIDEO_PRICE." WHERE id = $user_id" );

		echo "+ Achat d'une vidéo à ".VIDEO_PRICE."€ par l'utilisateur : $user_id\n";
	}
	elseif ( $type == 'refund' ) {
		mysql_query( "UPDATE balance SET montant = montant - ".VIDEO_REFUND." WHERE id = 1" );
		mysql_query( "UPDATE user SET tickets = tickets + ".VIDEO_REFUND." WHERE id = $user_id" );

		echo "- Renboursement de ".VIDEO_REFUND."€ pour l'utilisateur : $user_id\n";
	}
	else {
		exit( "! Aucun type valide spécifié ( buy | refund )\n" );
	}
}

$buyers = mysql_query( "SELECT * FROM buy WHERE status = 1 ORDER BY date ASC" );
while ( $buyer = mysql_fetch_assoc( $buyers ) ) {
	balance( $buyer['user_id'], 'buy' );

	mysql_query( "UPDATE buy SET status = 0 WHERE id = $buyer[id]" );

	$sql = "SELECT * FROM bet WHERE `date` < '".$buyer['date']."' AND `video_id` = ".$buyer['video_id']." AND `status` > 0 ORDER BY date ASC LIMIT 1";
	$refunders = mysql_query( $sql );
	while ( $refunder = mysql_fetch_assoc( $refunders ) ) {
		// si le refund est complét on désactive le bet
		if ( $refunder['status'] == 2 ) {
			balance( $refunder['user_id'], 'refund' );
			mysql_query( "UPDATE bet SET status = 0 WHERE id = $refunder[id]" );
		}
		// sinon on remet en jeux
		elseif ( $refunder['status'] == 1 ) {
			balance( $refunder['user_id'], 'refund' );
			mysql_query( "UPDATE bet SET status = 2 WHERE id = $refunder[id]" );
		}
	}


}