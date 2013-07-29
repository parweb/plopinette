<?php

session_start();

$SQL = '';

class Beer {
	public static function required () {
		require_once 'Config.php';
		require_once 'functions.php';
	}

	public static function drink () {
		global $SQL;

		self::required();

		Config::init();
		Config::loadFiles();

		$SQL = sql::getInstance();

		self::run();
	}

	static public function run () {
		$Module_name = ucfirst( url('module') ).'Controler';

		$Controler = new $Module_name;
		$Controler->init();
	}
}