<?php

class MenuControler extends AppControler {
	public function before () {
		user::need( 'admin' );

		$this->breadcrump( 'Menu' );
	}

	public function after () {
		//
	}

	protected function submit () {
		if ( $this->validate() ) {
			$id = $this->save();

			if ( $id ) {
				url::redirect( "menu/list/id:$id/" );
			}
		}
	}

	public function viewAction () {
		$this->view( 'title', $this->nom );
	}

	public function addAction () {
		$this->view( 'title', 'Ajouter un menu' );
		$this->breadcrump( 'Ajouter' );

		$this->submit();
	}

	public function editAction () {
		$this->view( 'title', 'Edition d\'un menu' );
		$this->breadcrump( 'Editer' );

		$this->submit();
	}

	public function deleteAction () {
		$this->view( 'title', 'Supprimer un menu' );
		$this->breadcrump( 'Supprimer' );

		$this->delete( url(':id') );

		url::redirect( "menu/list/" );
	}

	public function listAction () {
		$this->view( 'title', 'Liste des menus' );
		$this->view( 'description', '' );
		$this->breadcrump( 'Liste' );
	}
}

/*
$menu_id = $URL['where']['menu_id'];

$Menu = new Menu( $menu_id );

$TPL['breadcrump'][] = _( 'Menu' );

switch ( $URL['action'] ) {
	case 'edit':
	case 'add':
		user::need( array( 'admin' ) );

		if ( $URL['action'] == 'add' ) {
			$TPL['title'] = _( 'Ajouter un menu' );
			$TPL['breadcrump'][] = _( 'Ajouter' );
		}
		else {
			$TPL['title'] = _( 'Modifier un menu' );
			$TPL['breadcrump'][] = _( 'Modifier' );
		}

		if ( $_POST['action'] == 'add_menu' ) {
			$Nom = strip_tags( addslashes( $_POST['Nom'] ) );
			$Sub = strip_tags( addslashes( $_POST['Sub'] ) );
			$Uri = strip_tags( addslashes( $_POST['Uri'] ) );
			$Order = strip_tags( addslashes( $_POST['Order'] ) );

			$new_menu = array(
				'nom' => $Nom,
				'sub' => $Sub,
				'uri' => $Uri,
				'order' => $Order
			);

			if ( $URL['action'] == 'add' ) {
				$new_menu_id = $Menu->add( $new_menu );
			}
			else {
				$new_menu_id = $menu_id;
				$Menu->save( $new_menu_id, $new_menu );
			}

			url::redirect( link::href( 'menu', 'list' ) );
		}
	break;

	case 'delete':
		user::need( array( 'admin' ) );

		$TPL['title'] = _( 'Supprimer un menu' );
		$TPL['breadcrump'][] = _( 'Supprimer' );

		$Menu->delete( $menu_id );

		if ( $menu_id > 0 ) {
			url::redirect( link::href( 'menu', 'list' ) );
		}
	break;

	default:
		user::need( array( 'admin' ) );

		$TPL['title'] = _( 'Liste des menus' );
		$TPL['description'] = '';
		$TPL['breadcrump'][] = _( 'Liste' );

		$list = $Menu->listes( $APPLI );
	break;
}
*/