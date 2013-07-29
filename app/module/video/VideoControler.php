<?php

class VideoControler extends AppControler {
	public function before () {
		$this->breadcrump( 'Films' );
	}

	public function viewAction () {
		if ( !url( 'ajax' ) ) {
			url::redirect( 'video/infos/id:'.url( ':id' ).'/' );
		}

		user::need( 'member' );
	}

	public function infosAction () {
		$this->view( 'title', $this->Video->title.' ('.current( explode( '-', $this->Video->release ) ).')' );
		$this->view( 'description', $this->Video->abstract );

		$this->view( 'image', img::get( 'video', $this->Video->image ) );
		$this->view( 'description', $this->Video->abstract );

		$this->view( 'alert_status', alert::status( $this->Video->id ) );

		// temporaire
		$token = 'api_key=5f06a6c70d8a745836f7e2e71b41618f';

		$tmdb_id = $this->Video->tmdb_id;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://api.themoviedb.org/3/movie/$tmdb_id/images?$token");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json"));
		$response = curl_exec($ch);
		curl_close($ch);
		 
		$images = json_decode( $response, true );

		$this->view( 'backdrops', $images['backdrops'][rand( 0, count( $images['backdrops'] ) - 1 )]['file_path'] );
	}

	public function save () {
		user::need( 'admin' );

		if ( isset( $_FILES['video'] ) ) {
			$name = $_FILES['video']['name']['image'];
			$tmp_name = $_FILES['video']['tmp_name']['image'];

			$extention = end( explode( '.', $name ) );

			$new_file_dir = $this->upload;
			$new_file_name = time().'-'.rand( 10000, 99999 ).'-'._::clean( $_POST['video']['title'] ).'.'.$extention;

			$new_file = $new_file_dir.$new_file_name;

			if ( move_uploaded_file( $tmp_name, $new_file ) ) {
				$_POST['video']['image'] = $new_file_name;

				return parent::save();
			}
		}
	}

	public function addAction () {
		user::need( 'admin' );

		$this->view( 'title', 'Ajouter un film' );
		$this->breadcrump( 'Ajouter' );

		$this->submit();
	}

	public function editAction () {
		user::need( 'admin' );

		$this->view( 'title', 'Edition d\'un film' );
		$this->breadcrump( 'Editer' );

		$this->submit();
	}

	public function deleteAction () {
		user::need( 'admin' );

		$this->view( 'title', 'Supprimer un film' );
		$this->breadcrump( 'Supprimer' );

		$id = $this->delete( url(':id') );

		url::redirect( "video/list/" );
	}

	public function listAction () {
		//user::need( 'admin' );

		$this->view( 'title', 'Liste des '.video::count().' films' );
		$this->view( 'description', 'Film en streaming gratuit et entier de haute qualité HD, box office, Comédie, Drame, Thriller, Action, Aventure, Romance, Fantastique, Epouvante-horreur, Policier, Science fiction, Comédie dramatique, Animation, Famille, Biopic, Historique, Divers, Guerre, Musical, Western, Espionnage, Documentaire, Comédie musicale, Arts Martiaux, Erotique, Péplum, Drame, Romance, Sport event' );
		$this->breadcrump( 'Liste' );

		$this->view( 'features', $this->Video->listes( array( 'where' => 'video.feature = 1' ) ) );

		if ( count( $this->view->list ) == 1 && $this->view->pages == 1 ) {
			url::redirect( "video/infos/id:{$this->view->list[0]->id}/" );
		}
	}

	public function seenAction () {
		user::need( 'member' );

		$this->view( 'description', 'Liste des films que vous avez déjà vus' );
		$this->breadcrump( 'vus' );

		$url = url::url2params();
		$appli = url::url2appli( $url );
		$appli['select'] = 'video.*';
		$appli['from'] = 'buy buy';
		$appli['where'] = 'buy.video_id = video.id AND buy.user_id = '.user::id();

		$this->view( 'list', $this->Video->listes( $appli ) );

		$appli_count = $appli;
		unset( $appli_count['limit'] );
		unset( $appli_count['page'] );

		$count = $this->Video->listes( $appli_count, 'count' );
		$pages = ceil( $count / $url['limit'] );

		$this->view( 'count', $count );
		$this->view( 'pages', $pages );

		$this->view( 'title', "Vous avez vus les $count films suivant" );
	}

	public function searchAction () {
		$q = $_POST['q'];

		$this->view( 'title', 'Liste des films : '.$q );
		$this->view( 'description', '' );
		$this->breadcrump( 'Search' );

		$qs = explode( ' ', $q );

		$where = "search:!";
		foreach ( $qs as $i => $q ) {
			$q = clean( $q, array( "'" ) );
			$q = str_replace( ' ', '+', $q );

			$where .= "$q!/";

			if ( $i != count( $qs ) - 1 ) {
				$where .=  "search:!";
			}
		}

		url::redirect( "video/list/".$where."view:desc/" );
	}

	public function adminAction () {
		user::need( 'admin' );

		$this->view( 'title', 'Liste des films' );
		$this->view( 'description', '' );
		$this->breadcrump( 'Liste' );
	}

	public function accueilAction () {
		switch ( config('view.template') ) {
			case 'light':
			case 'default':
				url::redirect( "video/list" );
			break;
		}

		$this->view( 'title', 'Liste des films' );
		$this->view( 'description', '' );
		$this->breadcrump( '' );
	}
}