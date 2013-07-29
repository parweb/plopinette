<?php

class BetModel extends CoreModel {
	public $fields = array();

	public $belong = array( 'user', 'video' );

	public $one = array( 'date', 'status' );

	public function add ( $video_id ) {
		$array = array(
			'video_id' => $video_id,
			'user_id' => user::id(),
			'status' => 1
		);

		user::set( 'actions', '- 1' );

		return parent::add( $array );
	}

	public function validate ( $video_id ) {
		// on verifie qu'un utilisateur peut parier sur la vidéo.
		$return = false;
		$errors = array();

		// 1. on vérif si il est logué
		if ( !user::is_login() ) {
			$errors[] = _::t('L\'utilisateur n\'est pas logué');
		}

		// 2. on regarde si il a assez d'action
		if ( user::get('actions') - 1 < 0 ) {
			$errors[] = _::t('L\'utilisateur n\'a pas assez d\'actions');
		}

		// 3. on regarde si il na pas déja parié sur la video
		$bets = $this->listes( array( 'where' => array( 'bet.video_id = '.$video_id, 'bet.user_id = '.user::get('id') ) ) );
		if ( count( $bets ) ) {
			$errors[] = _::t('L\'utilisateur à déjà parié sur cette vidéo');
		}

		return $errors;
	}
}