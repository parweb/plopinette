<?php

class CoreView {
	protected $data = array();

	public function add ( $key, $val = null ) {
		if ( is_array( $key ) ) {
			$this->data = array_merge( $data, $key );
		}
		else if ( is_string( $key ) ) {
			$this->data[$key] = $val;
		}
	}

	public function __get ( $key ) {
		return ( isset( $this->data[$key] ) ) ? $this->data[$key] : null;
	}

	public function render ( $module = _MODULE, $action = _ACTION, $element = false ) {
		$more_module = ( $element ) ? '' : ucfirst( $module );

		return DIR.APP.MODULE.strtolower($module).DS.'tpl'.DS.$action.$more_module.'.tpl.php';
	}

	public function layout ( $layout = null, $template = null, $params = array() ) {
		$template = ( $template ) ? $template : config( 'view.template' );
		$_layout = ( url('action') == 'rss' ) ? url('action') : $layout;

		return DIR.APP.TEMPLATE.$template.DS.$_layout.'.tpl.php';
	}

	public function run ( $Controler ) {
		extract( $this->data );

		$layout = $this->layout( $Controler->layout, $Controler->template );
		$action = $this->render( $Controler->module, $Controler->action );

		if ( file_exists( $layout ) ) {
			include $layout;
		}
		else {
			//echo '!file_exists: $layout : '.$layout;
			if ( file_exists( $action ) ) {
				//echo 'file_exists: $action : '.$action;
				include $action;
			}
			else {
				//echo '!file_exists: $action : '.$action;
				exit('le fichier n\'existe pas: '.$action);
			}
		}
	}
}