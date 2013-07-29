<?php

class --Name--Model extends CoreModel
{
	public $id;
	public $title;
	public $content;
	public $date;
	public $status;

	public function __construct ( $id = '' )
	{
		$this->_table = array(
			//'hasMany' => array( 'comment' ),
			//'belongTo' => array( 'user' )
		);

		parent::__construct( $id );
	}

	public function install () {
		return "
		CREATE TABLE `--name--` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`title` VARCHAR( 255 ) NOT NULL ,
			`content` TEXT NOT NULL ,
			`date` DATETIME NOT NULL,
			`status` INT( 2 ) NOT NULL
			PRIMARY KEY (`id`)
		) ENGINE = MYISAM ;
		";
	}

	public function uninstall () {
		return "
		DROP TABLE `--name--`;
		";
	}
}