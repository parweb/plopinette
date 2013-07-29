<?php

$userModel = array();

class user {
	public static $alias = array(
		1 => 'member',
		2 => 'premium',
		4 => 'moderator',
		5 => 'admin'
	);
	static public function needLogin () {
		if ( !self::is_login() && config('site.auth') ) {
			url::redirect( 'user/login', URI );
		}
	}

	static public function is_login () {
		global $_SESSION,  $_COOKIE;

		$User = self::get();

		if ( !$User ) {
			return false;
		}

		$return = false;
		if ( isset( $_SESSION['user'] )  && $_SESSION['user']['session'] == $User->sessioncode ) {
			$return = (int)$User->id;
		}

		if ( !$return && isset( $_COOKIE['user_session'] ) && $_COOKIE['user_session'] == $User->sessioncode ) {
			$return = (int)$User->id;
		}

		return (bool)$return;
	}

	static public function needAdmin () {
		if ( !self::is_admin() && config('site.auth') ) {
			url::redirect( 'user/login', URI );
		}
	}

	static public function get ( $key = null ) {
		//$return = self::getModel( self::sessioncode2userid() );

		if ( !self::sessioncode2userid() ) {
			return false;
		}

		$return = new UserModel( self::sessioncode2userid() );

		$token = null;
		if ( isset( $_SESSION['token'] ) ) {
			$token = $_SESSION['token'];
		}

		if ( is_null( $token ) && isset( $_COOKIE['token'] ) ) {
			$token = $_COOKIE['token'];
		}

		if ( !is_null( $token ) && $return->id ) {
			$godfather_id = self::denjoy( $token );

			if ( $return->id != $godfather_id ) {
				$Sponsorship = new SponsorshipModel;

				$verif_exist = $Sponsorship->verif_exist( "sponsorship.slave_id = $return->id" );

				if ( !$verif_exist ) {
					$Sponsorship->add( array(
						'godfather_id' => $godfather_id,
						'slave_id' => $return->id
					));
				}
			}
		}

		if ( $key == 'status' && $return->$key > 0 ) {
			$return = self::$alias[$return->$key];
		}
		elseif ( $key != null ) {
			$return = $return->$key;
		}


		return $return;
	}

	static public function premium () {
		return strtotime( user::get( 'end_premium' ) ) > time();
	}

	static public function sponsor ( $id = false ) {
		$id = ( $id ) ? $id :  user::id();

		$Sponsorship = new SponsorshipModel;
		return $Sponsorship->verif_exist( "sponsorship.slave_id = $id" );
	}

	static public function gains ( $id = false ) {
		$id = ( $id ) ? $id :  user::id();

		$appli = array();
		$appli['select'] = 'SUM( transaction.price ) as gains';
		$appli['from'] = 'sponsorship sponsorship';
		$appli['where'] = array(
			'sponsorship.godfather_id = '.$id,
			'sponsorship.slave_id = transaction.user_id',
			'sponsorship.status = 1',
			'transaction.status = 1'
		);
		$appli['orderby'] = 'transaction.date ASC';

		$Transaction = new TransactionModel;
		return $Transaction->listes( $appli, 'extract', 'gains' ) * 2 / 3;
	}

	static public function gains_json ( $id = false ) {
		$id = ( $id ) ? $id :  user::id();

		$appli = array();
		$appli['select'] = 'transaction.*, transaction.price as price';
		$appli['from'] = 'sponsorship sponsorship';
		$appli['where'] = array(
			'sponsorship.godfather_id = '.$id,
			'sponsorship.slave_id = transaction.user_id',
			'sponsorship.status = 1',
			'transaction.status = 1'
		);
		$appli['orderby'] = 'transaction.date ASC';

		$Transaction = new TransactionModel;
		$list = $Transaction->listes( $appli );

		return _::chart( $list, 'date', 'price' );
	}

	static public function enjoy ( $id = false ) {
		$id = ( $id ) ? $id :  user::id();

		return str_replace( '=', '', base64_encode( $id ) );
	}

	static public function denjoy ( $code ) {
		return base64_decode( $code.'==' );
	}

	static public function set ( $key, $value = null ) {
		$User = new UserModel( self::sessioncode2userid() );

		if ( is_array( $key ) ) {
			$array = $key;
		}
		else {
			$array = array(
				$key => $value
			);
		}

		$User->save( $User->id, $array );
	}

	static public function id () {
		return self::get( 'id' );
	}

	static public function login () {
		return self::get( 'login' );
	}

	static public function is_admin () {
		$return = false;

		if ( self::is_login() ) {
			$User = self::get();

			if ( $User->status == 5 ) {
				$return = true;
			}
		}

		return $return;
	}

	static public function needSecretaire () {
		if ( !self::is_secretaire() ) {
			url::redirect( 'user/login', URI );
		}
	}

	static public function is_secretaire () {
		$return = false;

		if ( self::is_login() ) {
			$User = self::get();

			if ( $User->status == 4 ) {
				$return = true;
			}
		}

		return $return;
	}

	static public function need () {
		$array = (array)func_get_args();

		if ( in_array( 'member', $array ) ) {
			$array[] = 'premium';
			$array[] = 'admin';
		}

		if ( in_array( 'premium', $array ) ) {
			$array[] = 'admin';
		}

		if ( $array[0] == 'login' ) {
			self::needLogin();
			return true;
		}

		$status = self::get( 'status' );

		if ( $status == null || ( !in_array( $status, $array ) && config('site.auth') ) ) {
			url::redirect( 'user/login', URI );
		}
	}

	static public function is () {
		$array = (array)func_get_args();

		$return = false;

		if ( self::is_login() ) {
			$status = self::get( 'status' );

			if ( in_array( $status, $array ) ) {
				$return = true;
			}
		}

		return $return;
	}

	static public function getModel ( $id = 'null' ) {
		global $userModel;

		if ( $id == '' ) {
			$key_id = 'null';
		}
		else {
			$key_id = 'user_'.$id;
		}

		$userModelTmp = $userModel[$key_id];

		if ( $userModelTmp ) {
			return $userModelTmp;
		}
		else {
			if ( $id != 'null' ) {
				$userModel[$key_id] = new UserModel( $id );
			}
			else {
				$userModel[$key_id] = new UserModel;
			}

			return $userModel[$key_id];
		}
	}

	static public function sessioncode2userid ( $sessioncode = null ) {
		global $_SESSION, $_COOKIE;

		if ( $sessioncode == null ) {
			global $_SESSION, $_COOKIE;

			$sessioncode = ( isset( $_SESSION['user']['session'] ) ) ? $_SESSION['user']['session'] : null;

			if ( !$sessioncode ) {
				$sessioncode = ( isset( $_COOKIE['user_session'] ) ) ? $_COOKIE['user_session'] : null;
				$_SESSION['user']['session'] = $sessioncode;
			}
		}

		$appli['select'][] = "user.id";
		$appli['where'][] = "user.sessioncode = '$sessioncode'";

		//$User = self::getModel();
		$User = new UserModel;

		$return = false;
		if ( $sessioncode != null ) {
			$return = (int)$User->listes( $appli, 'extract', 'id' );
		}

		return $return;
	}

	static private function userurl2usergroup ( $user_url = '' ) {
		if ( $user_url == '' ) {
			$user_url = self::is_login();
		}

		$appli['select'][] = "ug.group_url";
		$appli['where'][] = "u.user_url = '$user_url'";

		return self::listes( $appli, 'extract', 'group_url' );
	}

	static public function listes ( $appli = array(), $sortie = '', $field = '' ) {
		$appli['form'][] = "usergroup ug";
		$appli['where'][] = "ug.usergroup_id = u.usergroup_id";

		return parent::listes( $appli, $sortie, $field );
	}

	static public function getAdmin ( $field ) {
		global $User;

		$out = ( !empty( $field ) ) ? 'extract' : 'first';

		return $User->listes( array( 'where' => 'user.status = 5' ), $out, $field );
	}

	private function create_admin () {

	}
}