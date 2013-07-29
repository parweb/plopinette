<?php

class AlertModel extends CoreModel {
	public $belong = array( 'user', 'video' );
	
	public $one = array( 'status', 'date' );
	
	public $order = 'video_id ASC, date ASC';
}