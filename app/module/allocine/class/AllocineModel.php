<?php

include_once( 'AllocineImgModel.php' );

class AllocineApi {
    private $_api_url = 'http://api.allocine.fr/rest/v3';
    private $_partner_key = '100043982026';
    private $_secret_key = '29d185d98c984a359e6e6f26a0474269';
    private $_user_agent = 'Dalvik/1.6.0 (Linux; U; Android 4.2.2; Nexus 4 Build/JDQ39E)';

    public function __construct() {}

    private function _do_request($method, $params)
    {
        // build the URL
        $query_url = $this->_api_url.'/'.$method;

        // new algo to build the query
        $sed = date('Ymd');
        $sig = urlencode(base64_encode(sha1($this->_secret_key.http_build_query($params).'&sed='.$sed, true)));
        $query_url .= '?'.http_build_query($params).'&sed='.$sed.'&sig='.$sig;

        // do the request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $query_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->_user_agent);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    public function search($query)
    {
        // build the params
        $params = array(
            'partner' => $this->_partner_key,
            'q' => $query,
            'format' => 'json',
            'filter' => 'movie'
        );

        // do the request
        $response = $this->_do_request('search', $params);

        return $response;
    }

    public function get($id)
    {
        // build the params
        $params = array(
            'partner' => $this->_partner_key,
            'code' => $id,
            'profile' => 'large',
            'filter' => 'movie',
            'striptags' => 'synopsis,synopsisshort',
            'format' => 'json',
        );

        // do the request
        $response = $this->_do_request('movie', $params);

        return $response;
    }
}


class AllocineModel {
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
	public $allocine_trailer;
	public $allocine_boxOffice;

	public $films;

	public $msg;

	private $allocine_fiche;
	private $file;

	public $fields = array();
	public $belong = array();
	public $one = array();

	public function __construct ( $req, $type = 'large' ) {
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

			$allocineapi = new AllocineApi;
			$myFile = $allocineapi->get( $this->allocine_id );

			$this->allocine_fiche = json_decode( $myFile, true );
		}
		else {
			$this->msg = "Aucun id d'allocine spécifié !";
		}
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
		if ( !isset( $this->allocine_fiche['error'] ) ) {
			$this->allocine_titre = $this->allocine_fiche['movie']['title'];
		}
	}

	public function getTitleOriginal () {
		if ( !isset( $this->allocine_fiche['error'] ) ) {
			$this->allocine_titreOriginal = $this->allocine_fiche['movie']['originalTitle'];
		}
	}

	public function getDateSortie () {
		if ( !isset( $this->allocine_fiche['error'] ) ) {
			$this->allocine_dateSortie = $this->allocine_fiche['movie']['release']['releaseDate'];

			if ( empty( $this->allocine_dateSortie ) ) {
				$this->allocine_dateSortie = $this->allocine_fiche['movie']['productionYear'].'-01-01';
			}

			if ( empty( $this->allocine_dateSortie ) ) {
				$this->allocine_dateSortie = '0000-00-00';
			}
		}
	}

	public function getRealisateur () {
		if ( !isset( $this->allocine_fiche['error'] ) ) {
			$list = (array)$this->allocine_fiche['movie']['castMember'];

			$directors = array();
			foreach ( $list as $item ) {
				if ( $item['activity']['code'] == 8002 ) {
					$code = $item['person']['code'];
					$name = $item['person']['name'];

					$directors[$code] = $name;
				}
			}

			$this->allocine_realisateur = $directors;
		}
	}

	public function getActeurs () {
		if ( !isset( $this->allocine_fiche['error'] ) ) {
			$list = (array)$this->allocine_fiche['movie']['castMember'];

			$actors = array();
			foreach ( $list as $item ) {
				if ( $item['activity']['code'] == 8001 ) {
					$code = $item['person']['code'];
					$name = $item['person']['name'];

					$actors[$code] = $name;
				}
			}

			$this->allocine_acteurs = $actors;
		}
	}

	public function getLangue () {
		if ( !isset( $this->allocine_fiche['error'] ) ) {
			$list = (array)$this->allocine_fiche['movie']['nationality'];

			$langs = array();
			foreach ( $list as $item ) {
				$code = $item['code'];
				$name = $item['$'];

				$langs[$code] = $name;
			}

			$this->allocine_langue = $langs;
		}
	}

	public function getGenre () {
		if ( !isset( $this->allocine_fiche['error'] ) ) {
			$list = (array)$this->allocine_fiche['movie']['genre'];

			$genres = array();
			foreach ( $list as $item ) {
				$code = $item['code'];
				$name = $item['$'];

				$genres[$code] = $name;
			}

			$this->allocine_genre = $genres;
		}
	}

	public function getDuree () {
		if ( !isset( $this->allocine_fiche['error'] ) ) {
			$runtime = $this->allocine_fiche['movie']['runtime'];

			$duree = '';
			if ( isset( $runtime ) ) {
				$temp = $runtime;
				$Minutes = $temp / 60;
				$lHeure = floor( $Minutes / 60 );
				$lesMinutes = $Minutes % 60;
				$duree = $lHeure.'h '.$lesMinutes.'min';
		    }

			$this->allocine_duree = $duree;
		}
	}

	public function getSynopsis () {
		if ( !isset( $this->allocine_fiche['error'] ) ) {
			$this->allocine_synopsis = $this->allocine_fiche['movie']['synopsis'];
		}
	}

	public function getImage () {
		if ( !isset( $this->allocine_fiche['error'] ) ) {
			$this->allocine_image = $this->allocine_fiche['movie']['poster']['href'];

			if ( empty( $this->allocine_image ) ) {
				$this->allocine_image = 'http://images.allocine.fr/r_160_214/b_1_cfd7e1/commons/emptymedia/AffichetteAllocine.gif';
			}
		}
	}

	public function getImagesBig () {
		if ( !isset( $this->allocine_fiche['error'] ) ) {
			$this->allocine_imageBig = $this->allocine_fiche['movie']['poster']['href'];

			if ( empty( $this->allocine_imageBig ) ) {
				$this->allocine_imageBig = 'http://images.allocine.fr/r_160_214/b_1_cfd7e1/commons/emptymedia/AffichetteAllocine.gif';
			}
		}
	}

	public function getTrailer () {
		if ( !isset( $this->allocine_fiche['error'] ) ) {
			$this->allocine_trailer = $this->allocine_fiche['movie']['trailer']['href'];
		}
	}

	public function getBoxOffice () {
		if ( !isset( $this->allocine_fiche['error'] ) && isset( $this->allocine_fiche['movie']['boxOffice'] ) ) {
			$max = 0;
			foreach ( $this->allocine_fiche['movie']['boxOffice'] as $entre ) {
				if ( $entre['admissionCountTotal'] > $max ) {
					$max = $entre['admissionCountTotal'];
				}
			}

			$this->allocine_boxOffice = $max;
		}
	}

	public function getInfos ( $img = true ) {
		if ( !isset( $this->allocine_fiche['error'] ) ) {
			$this->getTitle();
			$this->getTitleOriginal();
			$this->getDateSortie();
			$this->getRealisateur();
			$this->getActeurs();
			$this->getLangue();
			$this->getGenre();
			$this->getDuree();
			$this->getSynopsis();
			$this->getTrailer();
			$this->getBoxOffice();
			if ( $img ) {
				$this->getImage();
				$this->getImagesBig();
			}

			$this->allocine_fiche = null;
			$this->file = null;
		}
	}
}