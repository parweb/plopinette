<?php

$url2params = null;

class url {
	public static function url2paramsProxy () {
		$URL_REQUEST = current( explode( '?', URI ) );
		$params = explode( DS, trim( str_replace( URL, '/', $URL_REQUEST ), DS ) );

		if ( count( $params ) > 0 ) {
			$array = array();

			$j = 0;
			foreach ( $params as $i => $param ) {
				if ( !empty( $param ) ) {
					if ( $i == 0 && !preg_match( '|:|', $param ) ) {
						$array['module'] = $param;
					}
					elseif ( $i == 1 && !preg_match( '|:|', $param ) ) {
						$array['action'] = $param;
					}
					else {
						if ( preg_match( '|:|', $param ) ) {
							list( $k, $v ) = explode( ':', $param );
						}

						if ( $k == 'limit' ) {
							$array['limit'] = $v;
						}
						elseif ( $k == 'page' ) {
							$array['page'] = $v;
						}
						elseif ( $k == 'admin' ) {
							$array['admin'] = true;
							$array['module'] = $v;
						}
						elseif ( $v == 'asc' || $v == 'desc' ) {
							$array['order'] = $array['module'].'.'.$k.' '.strtoupper( $v );
						}
						else {
							$array['where'][$j][$k] = urldecode( $v );
							$array[':'.$k] = urldecode( $v );

							$j++;
						}
					}
				}
			}
		}

		if ( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) {
			$array['ajax'] = true;
		}

		if ( !isset( $array['module'] ) ) {
			$array['module'] = _MODULE;
		}

		if ( !isset( $array['action'] ) ) {
			$array['action'] = _ACTION;
		}

		if ( !isset( $array['page'] ) ) {
			$array['page'] = 1;
		}

		if ( !isset( $array['limit'] ) ) {
			$array['limit'] = _LIMIT;
		}

		if ( !isset( $array['order'] ) ) {
			$module = ucfirst( $array['module'] ).'Model';
			$Model = new $module;

			$order = ( !empty( $Model->order ) ) ? $Model->order : $Model->primary().' ASC';

			$array['order'] = $array['module'].'.'.$order;
		}

		return $array;
	}

	public static function url2params () {
		global $url2params;

		if ( $url2params ) {
			return $url2params;
		}
		else {
			$url2params = self::url2paramsProxy();
			return $url2params;
		}
	}

	public static function url2appli ( $params ) {
		$appli = array();

		if ( is_array( $params ) ) {
			if ( isset( $params['module'] ) ) {
				$module = $params['module'];

			//	$appli['from'] = $module.' '.$module[0];
			}

			if ( isset( $params['limit'] ) && !isset( $params['page'] ) ) {
				$start = 0;
				$end = $params['limit'];

				$appli['limit'] = "$start, $end";
			}
			elseif ( isset( $params['page'] ) && !isset( $params['limit'] ) ) {
				$start = ( $params['page'] - 1 ) * _LIMIT;
				$end = $params['limit'];

				$appli['limit'] = "$start, $end";
			}
			elseif ( isset( $params['page'] ) && isset( $params['limit'] ) ) {
				$start = ( $params['page'] - 1 ) * $params['limit'];
				$end = $params['limit'];

				$appli['limit'] = "$start, $end";
			}

			if ( isset( $params['order'] ) ) {
				$appli['orderby'] = $params['order'];
			}

			if ( isset( $params['where'] ) ) {
				if ( count( $params['where'] ) > 0 ) {
					if ( 0 ) {
						foreach ( $params['where'] as $k => $v ) {
							if ( $v ) {
								$v = urldecode( $v );
								$appli['where'][] = "$module.$k = '$v'";
							}
						}
					}
					else {
						foreach ( $params['where'] as $item ) {
							foreach ( $item as $k => $v ) {
								if ( $v ) {
									$op = '='; $deli = '';
									if ( preg_match( '#!#', $v ) ) {
										$op = 'LIKE';
										$v = str_replace( '!', '', $v );

										$deli = '%';
									}

									$v = urldecode( $v );
									$appli['where'][] = "$module.$k $op '$deli$v$deli'";
								}
							}
						}
					}
				}
			}
		}

		return $appli;
	}

	public static function redirect ( $url = 1, $back = null ) {
		if ( isset( $_REQUEST['back'] ) ) {
			$url = $_REQUEST['back'];
		}

		$_url = $url;

		if ( is_integer( $url ) ) {
			$url = self::previous( $url );
		}

		$url = preg_replace( '#/+#', '/', str_replace( DS, '/', ( ( $url == '/' ) ? $url : URL.$url ) ) );

		if ( preg_match( '#^http#', $_url, $out ) ) {
			$url = $_url;
		}

		if ( $back ) {
			$url .= "?back=$back";
		}

		header( "location: $url" );

		exit;
	}

	public static function previous ( $i = 1 ) {
		global $_SESSION;

		return $_SESSION['history'][$i];
	}
}