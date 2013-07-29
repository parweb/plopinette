<?php

class collection {	
	static public function has ( $collection_id, $video_id ) {
		$Collectioner = new CollectionerModel;
		return $Collectioner->verif_exist( array( 'video_id = '.$video_id, 'collection_id = '.$collection_id, 'status = 1' ) );
	}
}