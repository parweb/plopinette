<?php

class AllocineImgModel {
	public $allocine_id;
	public $allocine_titre;
	public $allocine_titreOriginal;
	public $allocine_dateSortie;
	public $allocine_realisateur;
	public $allocine_acteurs;
	public $allocine_langue;
	public $allocine_genre;
	public $allocine_duree;
	public $allocine_synopsis;
	public $allocine_image;
	public $allocine_imageBig;

	public $films;

	public $msg;

	private $allocine_fiche;
	private $file;

	public $fields = array();
	public $belong = array();
	public $one = array();

	public function __construct ( $req ) {
		if ( $req != '' ) {
			if ( preg_match( '|^([0-9]*)$|', $req ) ) {
				$this->allocine_id = $req;
			}
			else {
				$req = @str_replace( ' ', '+', $req );

				$url = "http://www.allocine.fr/recherche/1/?q=$req";
				$file = file_get_contents( $url );

				if ( preg_match( '#résultats trouvés dans les titres de films#', $file ) ) {

					preg_match( '|<table class="totalwidth noborder purehtml">(.*)</table>|se', $file, $out );

					$films = explode('<!-- /ResType -->', $out[0] );
					array_pop( $films );

					$Films = array();

					foreach ( $films as $film ) {
						preg_match( "|/film/fichefilm_gen_cfilm=([0-9]*)\.html|U", $film, $out );
						$Film = new AllocineModel( (int)$out[1] );
						$Film->getInfos();

						$Films[] = $Film;
					}

					$this->films = array(
						'search' => $req,
						'result' => $Films
					);
				}
			}

			$myFile = @file_get_contents( "http://www.allocine.fr/film/fichefilm_gen_cfilm=".$this->allocine_id.".html" );
			$myFile = str_replace( "\n", '', $myFile );
			$myFile = str_replace( "\r", '', $myFile );
			$myFile = str_replace( "     ", '', $myFile );
			$this->file = str_replace( "\t", '', $myFile );

			@preg_match( '|<!-- /rubric -->(.*)<!-- /colcontent -->|se', $this->file, $out );

			if ( isset( $out[1] ) ) {
				$this->allocine_fiche = $out[1];
			}
			else {
				$this->allocine_fiche = $this->file;
			}
		}
		else {
			$this->msg = "Aucun id d'allocine spécifié !";
		}

		//parent::__construct();
	}

	public function add ( $video_id ) {
		$array = array(
			'video_id' => $video_id,
			'user_id' => user::id(),
			'status' => 1
		);

		user::set( 'tickets', '- 1' );

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
		$allocines = $this->listes( array( 'where' => array( 'allocine.video_id = '.$video_id, 'allocine.user_id = '.user::get('id') ) ) );
		if ( count( $allocines ) ) {
			$errors[] = _::t('L\'utilisateur à déjà acheté cette vidéo');
		}

		return $errors;
	}

	public function getTitle () {
		preg_match( '|<h1 itemprop="name">(.*)</h1>|U', $this->file, $out );

		$this->allocine_titre = trim( ( isset( $out[1] ) ) ? $out[1] : '' );
	}

	public function getTitleOriginal () {
		preg_match( '|<th>Titre original</th><td>(.*)</td><td class="cell_sep">|U', $this->file, $out );

		if ( !empty( $out[1] ) ) {
			$this->allocine_titreOriginal = trim( $out[1] );
		}
	}

	public function getDateSortie () {
		preg_match_all( '|([0-9]{4})-([0-9]{2})-([0-9]{2})|', $this->file, $out );

		if ( count( $out[0] ) ) {
			$this->allocine_dateSortie = "".$out[1][0]."-".$out[2][0]."-".$out[3][0];
		}
		else {
			$this->allocine_dateSortie = '0000-00-00';
		}
	}

	public function getRealisateur () {
		$allocine_fiche = strip_tags( $this->file );
		$allocine_fiche = $this->file;

		preg_match( '#Réalisé par(.*)Avec #U', $allocine_fiche, $out );

		$list = explode( ',', ( isset( $out[1] ) ) ? $out[1] : '' );

		$directors = array();
		foreach ( $list as $item ) {
			preg_match( '#title="(.*)" href="/personne/fichepersonne_gen_cpersonne=([0-9]*).html"#', $item, $o );

			if ( count( $o ) ) {
				$directors[$o[2]] = $o[1];
			}
		}

		$this->allocine_realisateur = $directors;
	}

	public function getActeurs () {
		$allocine_fiche = strip_tags( $this->file );
		$allocine_fiche = $this->file;

		preg_match( '#Avec (.*)plus#U', $allocine_fiche, $out );

		$list = explode( ',', ( isset( $out[1] ) ) ? $out[1] : '' );

		$actors = array();
		foreach ( $list as $item ) {
			preg_match( '#title="(.*)" href="/personne/fichepersonne_gen_cpersonne=([0-9]*).html"#', $item, $o );

			if ( count( $o ) ) {
				$actors[$o[2]] = $o[1];
			}
		}

		$this->allocine_acteurs = $actors;

	}

	public function getLangue () {
		$allocine_fiche = strip_tags( $this->file );
		$allocine_fiche = $this->file;

		preg_match( '#Nationalité(.*)</div></li></ul>#U', $allocine_fiche, $out );

		$list = explode( ',', ( isset( $out[1] ) ) ? $out[1] : '' );

		$langs = array();
		foreach ( $list as $item ) {
			preg_match( '#underline">(.*)</span>#', $item, $o );

			if ( count( $o ) ) {
				$langs[] = $o[1];
			}
		}

		$this->allocine_langue = $langs;
	}

	public function getGenre () {
		$allocine_fiche = strip_tags( $this->file );
		$allocine_fiche = $this->file;

		preg_match( '#Genre(.*)Nationalité#U', $allocine_fiche, $out );

		$list = explode( ',', ( isset( $out[1] ) ) ? $out[1] : '' );

		$genres = array();
		foreach ( $list as $item ) {
			preg_match( '#itemprop="genre">(.*)</span></span>#', $item, $o );

			if ( count( $o ) ) {
				$genres[] = $o[1];
			}
		}

		$this->allocine_genre = $genres;
	}

	public function getDuree () {
		preg_match( '#[0-9]{1,2}h [0-9]{1,2}min#',$this->file, $out );

		$duree = strip_tags( ( isset( $out[0] ) ) ? $out[0] : '' );
		$duree = str_replace( '.&nbsp;', '', $duree );
		$duree = str_replace( 'Durée : ', '', $duree );

		$this->allocine_duree = $duree;
	}

	public function getSynopsis () {
		preg_match( '|<p itemprop="description">(.*)</p>|U', $this->file, $out );

		$this->allocine_synopsis = ( isset( $out[1] ) ) ? $out[1] : '';
	}

	public function getImage () {
		preg_match( '|<img src=\'(.*)\' alt=\'(.*)\' title=\'(.*)\' itemprop="image" />|U', $this->file, $out );

		$this->allocine_image = $out[1];

		if ( empty( $this->allocine_image ) ) {
			$this->allocine_image = 'http://images.allocine.fr/r_160_214/b_1_cfd7e1/commons/emptymedia/AffichetteAllocine.gif';
		}
	}

	public function getImagesBig () {
		$url = "http://www.allocine.fr/film/fichefilm-$this->allocine_id/photos/";

		$file = file_get_contents( $url );

		preg_match_all( '|<a href="(/film/fichefilm-([0-9]*)/photos/detail/\?cmediafile=([0-9]*))">|U', $file, $out );

		if ( isset( $out[1][0] ) ) {

			$first = $out[1][0];

			$url = "http://www.allocine.fr/$first";

			$file = file_get_contents( $url );

			preg_match_all( '|http://images.allocine.fr/r_([0-9]*)_([0-9]*)/(.*)\.jpg|U', $file, $out );

			$this->allocine_imageBig = '';
			if ( !empty( $out[0][0] ) ) {
				$this->allocine_imageBig = $out[0][0];
			}
			else {
				$this->getImage();
				$this->allocine_imageBig = $this->allocine_image;
			}
		}
		else {
			$this->getImage();
			$this->allocine_imageBig = $this->allocine_image;
		}
	}

	public function getInfos ( $img = true) {
		$this->getTitle();
		$this->getTitleOriginal();
		$this->getDateSortie();
		$this->getRealisateur();
		$this->getActeurs();
		$this->getLangue();
		$this->getGenre();
		$this->getDuree();
		$this->getSynopsis();
		if ( $img ) {
			$this->getImage();
			$this->getImagesBig();
		}

		$this->allocine_fiche = null;
		$this->file = null;
	}

	static public function _print ( $code ) {
		$file = file_get_contents( "http://api.allocine.fr/rest/v3/movie?code=$code&profile=large&format=json&partner=YW5kcm9pZC12M3M" );

		$a = json_decode( $file, true );
		echo '<pre>'.__FILE__.' ( '.__LINE__.' ) ';
			print_r( $a );
		echo '</pre>';exit;
	}
}