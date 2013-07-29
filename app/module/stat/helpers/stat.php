<?php

class video {
	static public function get ( $id, $width = '100%', $height = '100%', $host = 'http://dailymatons.com/films/:file' ) {
		$file = basename( current( glob( "/home/downloads/ok/$id*" ) ) );

		$url = str_replace( ':file', $file, $host );

		if ( preg_match( '#\.mp4#', $file ) ) {
			return '<script type="text/javascript" src="http://cdn.sublimevideo.net/js/2fsqewf8.js"></script>
			<video id="myVideo" class="sublime sv_html5_fullscreen" width="'.$width.'" height="'.$height.'" data-name="" preload="none">
				<source src="'.$url.'" />
			</video>
			<script>
				$(document).ready(function(){
					sublimevideo.load();
				});
			</script>';
		}
		else {
			return '<object classid="clsid:67DABFBF-D0AB-41fa-9C46-CC0F21721616" width="'.$width.'" height="'.$height.'" codebase="http://go.divx.com/plugin/DivXBrowserPlugin.cab">
				<param name="custommode" value="none" />
				<param name="autoPlay" value="false" />
				<param name="src" value="'.$url.'" />

				<embed type="video/divx" src="'.$url.'" custommode="none" width="'.$width.'" height="'.$height.'" autoPlay="false"  pluginspage="http://go.divx.com/plugin/download/"></embed>
			</object>';
		}
	}

	static public function count () {
		$Video = new VideoModel;
		return $Video->listes( array(), 'count' );
	}

	static public function view ( $id ) {
		$Video = new VideoModel;
		$Video->save( $id, array( 'view' => '++' ) );

		$Buy = new BuyModel;
		$Buy->add( $id );
	}

	static public function watched ( $id ) {
		if ( user::is_login() ) {
			$Buy = new BuyModel;
			$exist = $Buy->verif_exist( array( "buy.video_id = $id", 'buy.user_id = '.user::id() ) );

			if ( $exist ) {
				return 'watched';
			}
			else {
				return false;
			}
		}
		else {
			return false;
		}
	}

	static public function genre ( $id, $type = 'recursif' ) {
		$option = url::url2params();
		$option = url::url2appli($option);

		$a = array();
		$a['select'] = 'video.*';
		$a['from'][] = 'genrer genrer';
		$a['where'][] = 'genrer.video_id = video.id';
		$a['where'][] = 'genrer.genre_id = '.$id;
		if ( $type != 'count' ) $a['limit'] = $option['limit'];
		$a['orderby'] = 'video.title ASC';

		$Video = new VideoModel;
		return $Video->listes( $a, $type );
	}

	static public function lang ( $id, $type = 'recursif' ) {
		$option = url::url2params();
		$option = url::url2appli($option);

		$a = array();
		$a['select'] = 'video.*';
		$a['from'][] = 'languer languer';
		$a['where'][] = 'languer.video_id = video.id';
		$a['where'][] = 'languer.lang_id = '.$id;
		if ( $type != 'count' ) $a['limit'] = $option['limit'];
		$a['orderby'] = 'video.title ASC';

		$Video = new VideoModel;
		return $Video->listes( $a, $type );
	}

	static public function artist ( $id, $type = 'recursif' ) {
		$option = url::url2params();
		$option = url::url2appli($option);

		$a = array();
		$a['select'] = 'video.*';
		$a['from'][] = 'actor actor';
		$a['where'][] = 'actor.video_id = video.id';
		$a['where'][] = 'actor.artist_id = '.$id;
		if ( $type != 'count' ) $a['limit'] = $option['limit'];
		$a['orderby'] = 'video.title ASC';

		$Video = new VideoModel;
		return $Video->listes( $a, $type );
	}

	static public function director ( $id, $type = 'recursif' ) {
		$option = url::url2params();
		$option = url::url2appli($option);

		$a = array();
		$a['select'] = 'video.*';
		$a['from'][] = 'director director';
		$a['where'][] = 'director.video_id = video.id';
		$a['where'][] = 'director.artist_id = '.$id;
		if ( $type != 'count' ) $a['limit'] = $option['limit'];
		$a['orderby'] = 'video.title ASC';

		$Video = new VideoModel;
		return $Video->listes( $a, $type );
	}

	static public function actor ( $id, $type = 'recursif' ) {
		$option = url::url2params();
		$option = url::url2appli($option);

		$a = array();
		$a['select'] = 'video.*';
		$a['from'][] = 'actor actor';
		$a['where'][] = 'actor.video_id = video.id';
		$a['where'][] = 'actor.artist_id = '.$id;
		if ( $type != 'count' ) $a['limit'] = $option['limit'];
		$a['orderby'] = 'video.title ASC';

		$Video = new VideoModel;
		return $Video->listes( $a, $type );
	}

	static public function date ( $d, $type = 'recursif' ) {
		$option = url::url2params();
		$option = url::url2appli($option);

		$a = array();
		$a['select'] = 'video.*';
		$a['where'][] = "video.release LIKE '$d-%'";
		if ( $type != 'count' ) $a['limit'] = $option['limit'];
		$a['orderby'] = 'video.title ASC';

		$Video = new VideoModel;
		return $Video->listes( $a, $type );
	}
}