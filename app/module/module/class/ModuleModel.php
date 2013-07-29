<?php

class ModuleModel extends CoreModel {
	public function add ( $array ) {
		// creation des fichiers
		$module_name = strtolower( $array['title'] );
		$module_Name = Ucfirst( $array['title'] );
		$dir_module = DIR_MODULE.$module_name.DS;

		$replacements = array(
			'--name--' => $module_name,
			'--Name--' => $module_Name,
		);

		// copie le repertoire et remplace les occurences
		_FILE::fill( 'app/module', $replacements );

		return parent::add( $array );
	}

	public function delete ( $id ) {
		$Module = new self( $id );

		// supprime le repertoire
		rmdir( DIR_MODULE.$Module->title.DS );

		return parent::delete( $id );
	}

	public $fields = array(
		'title' => array(
			'type' => 'text',
			'validate' => array(
				'max' => 255,
				'required' => true
			)
		)
	);

	public $belong = array( 'user' );

	public $one = array( 'date', 'status', 'order' );
}