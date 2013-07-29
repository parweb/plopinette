<?php

class actor {
	static public function title ( $id ) {
		$Artist = new ArtistModel( $id );
		return $Artist->firstname.' '.$Artist->lastname;
	}
}