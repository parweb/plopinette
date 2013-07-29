<?php

class AppControler extends CoreControler {
	public function init () {
		css::add(
			'reset',
			'zoombox',
			'print',
			'style'
		);

		js::add(
			'jquery',
			'script',
			'facebook',
			'zoombox'
		);

		parent::init();
	}
}