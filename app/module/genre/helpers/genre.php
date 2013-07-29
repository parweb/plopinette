<?php

class genre {
	static public function title ( $id ) {
		/*
		$Genre = new GenreModel;
		return $Genre->listes( array( 'select' => 'genre.title', 'where' => 'genre.id = '.$id, 'debug' => true ), 'extract', 'title' );
		*/
		$Genre = new GenreModel( $id );
		return $Genre->title;
	}
}