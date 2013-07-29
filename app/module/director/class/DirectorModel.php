<?php

class DirectorModel extends CoreModel {
	public $fields = array();

	public $many = array();

	public $belong = array( 'artist', 'video' );

	public $one = array();
}