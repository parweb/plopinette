<?php

class lang {
	static public function title ( $id ) {
		/*
		$Genre = new GenreModel;
		return $Genre->listes( array( 'select' => 'genre.title', 'where' => 'genre.id = '.$id, 'debug' => true ), 'extract', 'title' );
		*/
		$Lang = new LangModel( $id );
		return $Lang->title;
	}
}