<?php

class LanguerModel extends CoreModel {
	public $fields = array();

	public $many = array( 'lang', 'video' );

	public $belong = array( 'lang', 'video' );

	public $one = array();
}