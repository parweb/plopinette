<?php

class form {
	static public function formatID ( $id ) {
		return str_replace(array( '[', ']', '.' ), array( '', '', '_' ), $id );
	}

	static public function formatNAME ( $name ) {
		$part = explode( '.', $name );
		array_shift($part);

		if ( count( $part ) > 1 ) {
			$first = current( $part );

			array_shift( $part );

			$return = $first.'['.join( '][', $part ).']';
		}
		else {
			$return = $name;
		}

		return $return;
	}

	static public function formatTEXT ( $text ) {
		$text = str_replace( current( explode( '.', $text ) ).'.', '', $text );
		return self::formatID( $text );
	}

	static public function input ( $name, $type, $value = '' ) {
		@list( $action, $table, $field ) = explode( '.', $name );

		$id = self::formatID( $name );
		$name = self::formatNAME( $name );

		if ( isset( $_POST[$table][$field] ) && isset( $_SESSION['form']['errors'][$action] ) ) {
			$value = $_POST[$table][$field];
		}

		return '<input type="'.$type.'" id="'.$id.'" name="'.$name.'" class="form-'.$type.'" value="'.$value.'" />';
	}

	static public function text ( $name, $value = '', $params = array() ) {
		return self::none( self::input( $name, 'text', $value ), $name );
	}

	static public function numeric ( $name, $value = '', $params = array() ) {
		return self::none( self::input( $name, 'numeric', $value ), $name );
	}

	static public function password ( $name, $value = '', $params = array() ) {
		return self::none( self::input( $name, 'password', $value ), $name );
	}

	static public function date ( $name, $value = '', $params = array() ) {
		return self::none( self::input( $name, 'date', $value ), $name );
	}

	static public function hidden ( $name, $value = '', $params = array() ) {
		return self::input( $name, 'hidden', $value );
	}

	static public function upload ( $name, $value = '', $params = array() ) {
		return self::none( self::input( $name, 'file', $value ), $name );
	}

	static public function textarea ( $name, $value = '', $params = array() ) {
		$id = self::formatID( $name );
		$_name = self::formatNAME( $name );

		return self::none( '<textarea class="form-textarea" name="'.$_name.'" id="'.$id.'">'.$value.'</textarea>', $name );
	}

	static public function select ( $name, $options = array(), $select_id = '', $params = array() ) {
		$id = self::formatID( $name );

		if ( count( $options ) ) {
			$_options = '';

			foreach ( $options['datas'] as $option ) {
				$selected = '';
				if ( $select_id == $option[$options['id']] ) {
					$selected = ' selected="selected"';
				}

				if ( !empty( $option[$options['id']] ) && !empty( $option[$options['value']] ) ) {
					$_options .= '<option value="'.$option[$options['id']].'"'.$selected.'>'.$option[$options['value']].'</option>';
				}
			}
		}

		return self::none( '<select class="form-select" name="'.$name.'" id="'.$id.'"><option value="0"><i>Selectionnez</i></option>'.$_options.'</select>', $id );
	}

	static public function choise ( $name, $type, $value, $checked = false, $params = array() ) {
		$id = self::formatID( $name );
		$name = self::formatNAME( $name );

		$checked = ( $checked == true ) ? ' checked="checked"' : '';

		return '<label class="form-'.$type.'"><input type="'.$type.'" name="'.$name.'" value="'.$value.'" id="'.$id.'"'.$checked.' /><span>'.$value.'</span></label>';
	}

	static public function checkbox ( $name, $value, $checked = false, $params = array() ) {
		return self::none( self::choise( $name, 'checkbox', $value, $checked, $params ), $name );
	}

	static public function radio ( $name, $value, $checked = false, $params = array() ) {
		return self::none( self::choise( $name, 'radio', $value, $checked, $params ), $name );
	}

	static public function submit ( $text ) {
		return button::submit( $text );
	}

	static public function legend ( $text ) {
		return '<legend>'.$text.'</legend>';
	}

	static public function none ( $content, $name = '', $params = array() ) {
		$id = self::formatID( $name );
		$text = self::formatTEXT( $name );

		$label = '>';
		if ( !empty( $name ) ) {
			$label = ' for="'.$id.'">'._::t( $text );
		}

		$_label = '<label class="intitule"'.$label.'</label>';

		$_error = ''; $_class = '';
		if ( !empty( $name ) ) {
			@list( $action, $table, $field ) = explode( '.', $name );

			if ( isset( $_SESSION['form']['errors'][$action][$field] ) && isset( $_POST[$table] ) ) {
				$_error = '<div class="clear"></div><ul><li>'.join( '</li><li>', array_values( $_SESSION['form']['errors'][$action][$field] ) ).'</li></ul>';
				$_class = ' error';

				unset( $_SESSION['form']['errors'][$action][$field] );

				if ( !count( $_SESSION['form']['errors'][$action] ) ) {
					unset( $_SESSION['form']['errors'][$action] );
				}
			}
			elseif ( isset( $_SESSION['form']['success'][$action][$field] ) && $_POST[$table] ) {
				$_error = '<div class="clear"></div><ul><li>Ce champs est valide ;)</li></ul>';
				$_class = ' good';
			}

		}

		return '<div class="form'.$_class.'">
			'.$_label.'
			'.$content.'
			'.$_error.'

			<div class="clear"></div>
		</div>';
	}
}