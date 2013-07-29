<?php

class GenrerModel extends CoreModel {
	public $fields = array();

	public $many = array();

	public $belong = array( 'genre', 'video' );

	public $one = array();
}