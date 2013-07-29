<?php

class CoreControler {
	protected $breadcrump = array();
	public $view;

	public $module;
	public $action;

	public $template;
	public $layout;

	public $upload;

	public function init () {
		$module = url('module');
		$Module = ucfirst($module);
		$Module_name = $Module.'Controler';
		$Model = $Module.'Model';

		$action = url('action').'Action';
		$option = url('where');

		$this->$Module = new $Model( url(':id', 0) );

		$this->view = new CoreView;

		$this->module =  $module;
		$this->action = url('action');
		$this->layout = config('view.layout');
		$this->template = config('view.template');

		$css_module = APP.TEMPLATE.$this->template.DS.'css'.DS.'module'.DS.$this->module.'.css';
		if ( file_exists( $css_module ) ) css::add( 'module'.DS.$this->module );
		$js_module = APP.TEMPLATE.$this->template.DS.'js'.DS.'module'.DS.$this->module.'.js';
		if ( file_exists( $js_module ) ) js::add( 'module'.DS.$this->module );

		$this->upload = self::upload();

		$this->view( $Module, $this->$Module );

		$options = url::url2params();
		if ( !isset( $options['order'] ) ) {
			$order = ( !empty( $this->$Module->order ) ) ? $this->$Module->order : $this->$Module->primary().' ASC';
			$options['order'] = $module.'.'.$order;
		}

		$appli = url::url2appli( $options );

		$this->view( 'list', $this->$Module->listes( $appli ) );

		$options_without_limit = $options;
		unset( $options_without_limit['limit'] );
		unset( $options_without_limit['page'] );

		$count = $this->$Module->listes( url::url2appli( $options_without_limit ), 'count' );
		$pages = ceil( $count / $options['limit'] );

		$this->view( 'count', $count );
		$this->view( 'pages', $pages );

		$this->view( 'upload', array( 'dir' => $this->upload, 'url' => str_replace( DIR, '', $this->upload ) ) );

		$this->before();

		$Reflection = new ReflectionClass( $this );

		if ( $Reflection->hasMethod( $action ) ) {
			call_user_func( array( $this, $action ) );
		}
		else {
			exit( "La methode <strong style='color: red;'>{$Module_name}->$action( ".json_encode( $options )." )</strong> na put etre appelé <br />soit elle n'existe pas ou elle nest pas en public<br />le fichier devrait se trouver ".DIR.APP.MODULE.$module.DS.$Module."Controler.php ;)" );
		}

		$this->after();
		$this->render();
	}

	static public function upload () {
		$module = strtolower( str_replace( 'Controler', '', get_called_class() ) );

		return DIR.APP.MODULE.$module.DS.'upload'.DS;
	}

	public function after () {

	}

	public function render () {
		$this->view->run( $this );
	}

	public function before () {

	}

	public function validate ( $params = array() ) {
		if ( isset ( $_POST[$this->module] ) ) {
			$Module = ucfirst( $this->module );

			$datas = $_POST[$this->module];
			$fields = $this->$Module->fields;
			$validations = config( 'validations' );

			if ( count( $params ) ) {
				$validations = $params;
			}

			$shemas = array(); $errors = array(); $success = array();
			if ( count( $datas ) ) {
				foreach ( $datas as $data_field => $data_value ) {
					$shemas[$data_field] = $validations[$data_field];
					$error = array();

					foreach ( $validations[$data_field] as $validation_field => $validation_value ) {
						switch ( $validation_field ) {
							case 'required':
								if ( empty( $data_value ) || !isset( $data_value ) || $data_value == '' ) {
									$error[$validation_field] = 'Ce champs est obligatoire.';

									break(2);
								}
							break;

							case 'unique':
								$table = $this->$Module->name;
								$alias = $this->$Module->alias;

								$response = $this->$Module->verif_exist( array( "$alias.$data_field = '$data_value'" ) );

								$id = null;
								if ( isset( $datas['id'] ) ) {
									$id = $datas['id'];
								}

								if ( $response && $id != $response->id ) {
									$error[$validation_field] = "Ce $data_field à déjà été choisie, veuillez en proposer un autre.";
								}

							break;

							case 'exist':
								$table = $this->$Module->name;
								$alias = $this->$Module->alias;

								if ( !$this->$Module->verif_exist( array( "$alias.$data_field = '$data_value'" ) ) ) {
									$error[$validation_field] = "Ce $data_field n'existe pas.";
								}
							break;

							case 'password_match':
								$table = $this->$Module->name;
								$alias = $this->$Module->alias;

								$match = $datas[$validation_value];

								if ( !$this->$Module->verif_exist( array( "$alias.$data_field = '"._::crypt( $data_value )."'","$alias.$validation_value = '$match'" ) ) ) {
									$error[$validation_field] = "Ce mot de passe n'est pas valide pour ce $validation_value.";
								}
							break;

							case 'match':
								$table = $this->$Module->name;
								$alias = $this->$Module->alias;

								$match = $datas[$validation_value];

								if ( !$this->$Module->verif_exist( array( "$alias.$data_field = '$data_value'","$alias.$validation_value = '$match'" ) ) ) {
									$error[$validation_field] = "Ce $data_field n\'existe pas.";
								}
							break;

							case 'max':
								if ( strlen( $data_value ) > $validation_value ) {
									$error[$validation_field] = "$validation_value caractères maximum.";
								}
							break;

							case 'min':
								if ( strlen( $data_value ) < $validation_value ) {
									$error[$validation_field] = "$validation_value caractères minimum.";
								}
							break;

							case 'between':
								list( $max, $min ) = explode( ':', $validation_value );

								if ( $max < strlen( $data_value ) || strlen( $data_value ) < $min ) {
									$error[$validation_field] = "Le nombre de caractère doit être compris entre $min et $max.";
								}
							break;

							case 'regex':
								if ( !preg_match( "#$validation_value#", $data_value ) ) {
									$error[$validation_field] = 'Le champs est invalide.';
								}
							break;

							case 'text':
								switch ( $validation_value ) {
									case 'simple':
										if ( !preg_match( '#[a-zA-Z0-9\.-_]#', $data_value ) ) {
											$error[$validation_field] = 'Le champs doit contenir seulement des caractère alphanumerique et/ou _ - .';
										}
									break;

									case 'advanced':
										if ( 0 ) {
											$error[$validation_field] = 'Le champs doit contenir seulement les caractère suivant: x, x, x.';
										}
									break;

									case 'email':
										if( !filter_var( $data_value, FILTER_VALIDATE_EMAIL ) ) {  
										    $error[$validation_field] = 'Veuillez entrer un email valide.';
										}
									break;
								}
							break;
						}
					}

					if ( count( $error ) > 0 ) {
						$errors[$this->action][$data_field] = $error;
					}
					else {
						$success[$this->action][$data_field] = true;
					}
				}
			}

			if ( count( $errors ) ) {
				$_SESSION['form']['success'] = $success;
				$_SESSION['form']['errors'] = $errors;

				return false;
			}
			else {
				return true;
			}
		}
	}

	public function submit () {
		if ( $this->validate() ) {
			return $this->save();
		}
	}

	public function save () {
		$Module = ucfirst( $this->module );

		if ( isset( $_POST[$this->module]['id'] ) ) {
			$return = $this->$Module->save( $_POST[$this->module]['id'], $_POST[$this->module] );
		}
		else {
			$return = $this->$Module->add( $_POST[$this->module] );
		}

		return $return;
	}

	public function delete ( $id = null ) {
		$Module = ucfirst( $this->module );

		return $this->$Module->delete( $this->$Module->id );
	}

	public function breadcrump ( $data = null ) {
		if ( is_array( $data ) ) {
			$this->breadcrump = array_merge( $breadcrump, $data );
		}
		else if ( is_string( $data ) ) {
			$this->breadcrump[] = $data;
		}

		$this->view( 'breadcrump', $this->breadcrump );
	}

	public function view ( $key, $val = null ) {
		$this->view->add( $key, $val );
	}
}