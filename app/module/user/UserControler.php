<?php

class UserControler extends AppControler {
	public function before () {
		$this->breadcrump( 'User' );
	}
	
	public function testAction () {
		$array = array(
			'login' => 'parweb',
			'pass' => 'plop',
			'email' => 'parweb@gmail.com'
		);
		mail::send( $array['email'], 'add_user', $array );
		exit;
	}

	public function addAction () {
		$this->view( 'title', 'Ajouter un user' );
		$this->breadcrump( 'Ajouter' );

		if ( $id = $this->submit() ) {
			$User = new UserModel( $id );
			$User->login();

			url::redirect( 'user/edit' );
		}
	}

	public function editAction () {
		user::need( 'member' );

		$this->view( 'title', 'Modification de votre compte' );
		$this->breadcrump( 'Editer' );

		if ( $id = $this->submit() ) {
			$User = new UserModel( $id );
			$User->login();

			url::redirect( 'user/edit' );
		}
	}

	public function deleteAction () {
		user::need( 'admin' );

		$this->view( 'title', 'Supprimer un user' );
		$this->breadcrump( 'Supprimer' );

		if ( $this->delete() ) {
			url::redirect( "user/list/" );
		}
	}

	public function listAction () {
		user::need( 'admin' );

		$this->view( 'title', 'Liste des users' );
		$this->view( 'description', '' );
		$this->breadcrump( 'Liste' );
	}

	public function loginAction () {
		$this->view( 'title', '1 - Ouvrir un compte' );

		$Facebook = new Facebook( array(
			'appId'  => '200606866671822',
			'secret' => '0c2f4dd40f77e7c3e12d5d83822cd6cd'
		));

		$user_facebook = $Facebook->getUser();

		if ( $user_facebook ) {
			$infos = $Facebook->api('/me');

			$_POST['user'] = array(
				'email' => $infos['email'],
				'login' => $infos['username'],
				'facebook_id' => $infos['id'],
	            'pass' => uniqid()
			);

			$user_exist = $this->User->verif_exist( array( "user.email = '$infos[email]' OR user.facebook_id = $infos[id]" ) );

			if ( $user_exist ) {
				$TheUser = new UserModel( $user_exist->id );
				$TheUser->login();

				if ( !$TheUser->facebook_id ) {
					$TheUser->save( $TheUser->id, array( 'facebook_id' => $infos['id'] ) );
				}

				url::redirect( 'user/edit' );
			}
			else {
				$this->addAction();
			}
		}

		$validation = array(
			'login' => array(
				'required' => true,
				'exist' => true
			),
			'pass' => array(
				'required' => true,
				'password_match' => 'login'
			)
		);

		if ( $this->validate( $validation ) ) {
			$login = $_POST['user']['login'];
			$pass = $_POST['user']['pass'];

			$session_code = _::crypt( "<$login><$pass>" );

			$TheUser = new UserModel( user::sessioncode2userid( $session_code ) );

			if ( $TheUser->id > 0 ) {
				$TheUser->login();

				url::redirect( 'user/edit' );
			}
		}
	}

	public function logoutAction () {
		global $_SESSION;

		session_unset();
		$_SESSION['user'] = '';
		setcookie( 'user_session', '', null, '/' );

		url::redirect( 'video' );
	}

	public function exportAction () {
		user::need( 'admin' );

		$csv = array();
		foreach ( $this->User->listes() as $item ) {
			$csv[] = "$item->id;$item->facebook_id;$item->login;$item->pass;$item->sessioncode;$item->email;$item->status;$item->date;$item->tickets;$item->actions";
		}

		echo join( "\n", $csv );exit;
	}

	public function gainsAction () {
		user::need( 'member' );

		$this->view( 'title', 'Etat de vos gains' );
		$this->breadcrump( 'Gains' );
	}

	public function collectionsAction () {
		user::need( 'member' );

		$this->view( 'title', 'Liste de vos collections' );
		$this->breadcrump( 'Collections' );

		$Collection = new CollectionModel;
		$list = $Collection->listes( array( 'debug'=>true,'where' => 'collection.user_id = '.user::id() ) );

		$this->view( 'count', 1 );
		$this->view( 'pages', 1 );

		$this->view( 'list', $list );
	}

	public function desolerAction () {
		user::need( 'member' );

		$this->view( 'title', 'Désoler :/' );
		$this->breadcrump( 'desoler' );

		if ( $_POST['action'] == 'subscribe' ) {
			$duration = $_POST['duration'];

			if ( config( "subscription.prices.$duration" ) ) {
				$uid = user::id();

				$custom = "action=subscribe&uid=$uid&duration=$duration";
				$price = number_format( config( "subscription.prices.$duration" ), 2 );
				$name = "Compte premium $duration semaines";

				$Transaction = new TransactionModel;
				$url = $Transaction->requestPaypal( $price, $name, $custom );

				if ( $url ) {
					url::redirect( $url );
				}
			}
		}
	}
}