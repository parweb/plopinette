<?php

class alert {
	static public function count () {
		$Alert = new AlertModel;
		return $Alert->listes( array( 'where' => 'alert.status = 1' ), 'count' );
	}

	static public function status ( $video_id ) {
		$Alert = new AlertModel;
		
		$appli = array();

		if ( user::is_admin() ) {
			$appli['select'] = 'SUM(alert.status) as status';
			$appli['groupby'] = 'alert.status';
		}
		else {
			$appli['select'] = 'alert.status';
			$appli['where'][] = "alert.video_id = $video_id";
			$appli['where'][] = 'alert.user_id = '.user::id();
		}

		return (bool)$Alert->listes( $appli, 'extract', 'status' );
	}
}