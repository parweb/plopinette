<?php

class BuyModel extends CoreModel {
	public $fields = array();

	public $belong = array( 'user', 'video' );

	public $one = array( 'date', 'status' );

	public function add ( $video_id ) {
		$array = array(
			'video_id' => $video_id,
			'user_id' => user::id(),
			'status' => 1
		);

		user::set( 'tickets', '--' );

		return parent::add( $array );
	}

	public function validate ( $video_id ) {
		// on verifie qu'un utilisateur peut acheter une vidéo.
		$return = false;
		$errors = array();

		// 1. on vérif si il est logué
		if ( !user::is_login() ) {
			$errors[] = _::t('L\'utilisateur n\'est pas logué');
		}

		// 2. on regarde si il a assez d'argent
		if ( user::get('tickets') - 1 < 0 ) {
			$errors[] = _::t('L\'utilisateur n\'a pas assez de ticket');
		}

		// 3. on regarde si il na pas déja acheter la video
		$buys = $this->listes( array( 'where' => array( 'buy.video_id = '.$video_id, 'buy.user_id = '.user::get('id') ) ) );
		if ( count( $buys ) ) {
			$errors[] = _::t('L\'utilisateur à déjà acheté cette vidéo');
		}

		return $errors;
	}
}