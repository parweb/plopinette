<?php

class SponsorshipControler extends AppControler {
	public function enjoyAction () {
		global $_SESSION, $_COOKIE;

		if ( !isset( $_SESSION['token'] ) && !isset( $_COOKIE['token'] ) ) {
			setcookie( 'token', url( ':i' ), strtotime( '+10 year' ), '/' );
			$_SESSION['token'] = url( ':i' );
		}

		url::redirect( 'video/list' );
	}
}