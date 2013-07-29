<?php

class UserModel extends CoreModel {
	public $fields = array(
		'login' => array(
			'type' => 'text'
		),
		'pass' => array(
			'type' => 'text'
		),
		'sessioncode' => array(
			'type' => 'text'
		),
		'email' => array(
			'type' => 'text'
		),
		'tickets' => array(
			'type' => 'numeric'
		),
		'actions' => array(
			'type' => 'numeric'
		),
		'end_premium' => array(
			'type' => 'date'
		),
		'last_login' => array(
			'type' => 'date'
		)
	);

	public $one = array( 'date', 'status' );

	public $many = array( 'collection', 'buy' );

	public $display = 'login';

	public function add ( $array ) {
		$array['sessioncode'] =  _::crypt( "<$array[login]><$array[pass]>" );
		$array['pass'] =  _::crypt( $array['pass'] );
		$array['status'] = 2;

		//mail::send( $array['email'], 'add_user', $array );

		return parent::add( $array );
	}

	public function save ( $id, $array ) {
		if ( $array == 'last_login' ) {
			$array = array();
			$array['last_login'] = date( 'Y-m-d H:i:s' );
		}

		if ( isset( $array['login'] ) && isset( $array['pass'] ) ) {
			$array['sessioncode'] =  _::crypt( "<$array[login]><$array[pass]>" );
			$array['pass'] =  _::crypt( $array['pass'] );
		}

		return parent::save( $id, $array );
	}

	public function login () {
		$session_code = $this->sessioncode;

		setcookie( 'user_session', $session_code, strtotime( '+10 year' ), '/' );
		$_SESSION['user']['session'] = $session_code;

		$this->save( $this->id, 'last_login' );
	}

	public function premium () {
		return strtotime( $this->end_premium ) > time();
	}

	public function loginExist ( $login, $pass ) {
		$session_code = _::crypt( "<$login><$pass>" );

		$where[] = "user.login = '$login'";
		$where[] = "user.pass = '"._::crypt( $pass )."'";
		$where[] = "user.sessioncode = '$session_code'";

		$user = $this->verif_exist( $where );

		if ( $user ) {
			$user = new UserModel($user->id);
			return $user->sessioncode;
		}

		return false;
	}
}