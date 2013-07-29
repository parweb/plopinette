<?php

class CollectionerControler extends AppControler {
	public function addAction () {
		$validation = array(
			'collection_id' => array(
				'required' => true
			),
			'video_id' => array(
				'required' => true
			)
		);
	
		if ( $this->validate( $validation ) ) {
			$exist = $this->Collectioner->verif_exist( array( 'video_id = '.$_POST['collectioner']['video_id'], 'collection_id = '.$_POST['collectioner']['collection_id'] ), array( 'status' ) );

			if ( $exist ) {
				$_POST['collectioner']['id'] = $exist->id;
				$_POST['collectioner']['status'] = $exist->status == 1 ? 0 : 1;
			}

			$this->save();
		}
		else {
			exit('not valid');
		}
	}
}