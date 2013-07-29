<?php

class AlertControler extends AppControler {
	public function addAction () {
		user::need( 'member' );
		
		$exist = $this->Alert->verif_exist( array(
			'user_id = '.user::id(),
			'video_id = '.url( ':video_id' )
		), array( 'alert.status' ));
		
		if ( $exist ) {
			if ( user::is_admin() ) {
				//sql::query( 'UPDATE alert SET status = '.!$exist->status.' WHERE video_id = '.$exist->id );
				
				/*
				
				// Envoyer un email a tous ceux qui ont déclanché l'alert pour ce film
				
				if ( $exist->status == 1 ) {
					mail::send( 'user@com.com', 'resolv_alert', array( 'jesaispas' => 'dutoutpourlemoment' ) );
				}
				
				*/
				
				$this->Alert->save( 'alert.video_id = '.url(':video_id'), array(
					'status' => !$exist->status
				));
			}
			else {
				$this->Alert->save( $exist->id, array(
					'status' => !$exist->status
				));
			}

			$this->view( 'status', !$exist->status );
		}
		else {
			$this->Alert->add( array(
				'user_id' => user::id(),
				'video_id' => url( ':video_id' ),
				'status' => 1
			));
			
			$this->view( 'status', 1 );
		}
	}
	
	public function listAction () {
		user::need( 'admin' );

		$this->view( 'title', 'Liste des alertes' );
		$this->view( 'description', '' );
	}
}