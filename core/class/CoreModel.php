<?php

class CoreModel {
	public $name;
	public $alias;

	public $primary = array(
		'id' => array(
			'type' => 'primary'
		)
	);

	protected $_data = array();

	public $belong = array();
	public $many = array();
	public $one = array();

	public $display = 'title';
	public $fields = array();
	public $order;

	private $out;
	private $appli;

	public $sql = true;

	public function __construct ( $id = null ) {
		$this->setname();
		$this->alias = $this->name();

		$this->fields = array_merge( $this->primary, $this->fields );

		if ( !$this->table_existe() ) {
			$this->create_table();
		}

		if ( $id ) {
			$object = $this->listes( array( 'where' => $this->alias().'.'.$this->primary().' = '.$id, 'limit' => 1 ) );
			$object = $object[0];

			foreach ( $this->fields() as $name => $item ) {
				$this->$name = $object->$name;
			}

			foreach ( $this->belong() as $item ) {
				$class_name = ucfirst( $item ).'Model';
				$Class = new $class_name;

				$current_id = $Class->primary();

				$foreign_field = "{$item}_{$current_id}";

				$this->$item = $Class->listes( array( 'where' => array( "{$item}.{$current_id} = ".$this->$foreign_field ) ), 'first' );
			}

			foreach ( $this->many() as $k => $item ) {
				$appli = array();

				if ( !is_integer( $k ) ) {
					$pivot = $item;
					$item = $k;

					$appli['from'] = "$pivot $pivot";
					$appli['where'][] = "$pivot.{$item}_id = $item.id";
				}

				$class_name = ucfirst( $item ).'Model';
				$Class = new $class_name;

				$current_id = $this->primary();
				$current_class_name = $this->name();

				$foreign_field = $current_class_name.'_'.$current_id;

				$appli['where'][] = "{$item}.{$foreign_field} = ".$this->$current_id;
				//$appli['debug'] = true;

				$this->{$item} = $Class->listes( $appli, 'none' );
			}
		}
	}

	public function validate ( $x = null) {

	}

	public function fields () {
		$more = array();
		foreach ( $this->one() as $item ) {
			$more[$item] = array();
		}

		foreach ( $this->belong() as $item ) {
			$more[$item.'_id'] = array();
		}

		return array_merge( $this->fields, $more );
	}

	public function __set ( $key, $val ) {
		$this->_data[$key] = $val;

		return $this;
	}

	public function __get ( $key ) {
		return ( isset( $this->_data[$key] ) ) ? $this->_data[$key] : null;
	}

	private function name () {
		return strtolower( $this->name );
	}

	private function setname ( $name = '' ) {
		if ( isset( $this->name ) && $this->name != '' ) return false;

		$this->name = ( $name  == '' ) ? strtolower( str_replace( 'Model', '', get_class( $this ) ) ) : $name;
	}

	private function alias () {
		return strtolower( $this->alias );
	}

	public function primary () {
		$primary = array_keys( $this->primary );

		return $primary[0];
	}

	public function belong () {
		return $this->belong;
	}

	public function many () {
		return $this->many;
	}

	public function one () {
		return $this->one;
	}

	public function display () {
		return $this->display;
	}

	private function constructFrom () {
		$i = 0;

		$class = strtolower( $this->name() );

		$thisVal = $this->name();
		$thisAlias = $this->alias();
		$thisKey = $this->name();

		$defaut_alias = ( isset( $thisAlias ) ? $thisAlias : $class[0] );
		$defaut_val = ( isset( $thisVal ) ? $thisVal : $class );

		$out[$i]['alias'] = $defaut_alias;
		$out[$i]['table'] = $defaut_val;

		$i++;

		if ( isset( $this->appli['from'] ) && is_array( $this->appli['from'] ) ) {
			$froms = array_unique( $this->appli['from'] );

			foreach ( $froms as $from ) {
				list( $table, $alias ) = explode( ' ', $from );

				$out[$i]['alias'] = $alias;
				$out[$i]['table'] = $table;

				$i++;
			}
		}
		elseif ( isset( $this->appli['from'] ) && is_string( $this->appli['from'] ) ) {
			if ( !empty( $this->appli['from'] ) ) {
				list( $table, $alias ) = explode( ' ', $this->appli['from'] );

				$out[$i]['alias'] = $alias;
				$out[$i]['table'] = $table;
			}
		}

		return $out;
	}

	private function getSelect () {
		$froms = $this->constructFrom();

		if ( count( $froms ) ) {
			$out = '';
			foreach ( $froms as $from ) {
				$out .= $from['alias'].'.*, ';
			}
		}

		if ( isset( $this->appli['select'] ) && is_array( $this->appli['select'] ) ) {
			$selects = array_unique( $this->appli['select'] );

			$out = '';
			foreach ( $selects as $select ) {
				$out .= $select.", ";
			}
		}
		elseif ( isset( $this->appli['select'] ) && is_string( $this->appli['select'] ) ) {
			if ( !empty( $this->appli['select'] ) ) {
				$out = $this->appli['select'].", ";
			}
		}

		$out = 'SELECT '.trim( $out, ', ' );

		return $out;
	}

	private function getFrom () {
		$froms = $this->constructFrom();
		if ( count( $froms ) ) {
			$out = ' FROM ';

			foreach ( $froms as $from ) {
				$out .= $from['table'].' AS '.$from['alias'].", ";
			}
		}

		$out = trim( $out, ', ' );

		return ' '.$out;
	}

	private function getJoin () {
		foreach ( $this->many() as $many ) {
			$query = '';
		}
	}

	private function getWhere () {
		$out = '';

		if ( isset( $this->appli['where'] ) && is_array( $this->appli['where'] ) ) {
			$this->appli['where'] = array_unique( $this->appli['where'] );

			$out = '';
			foreach ( $this->appli['where'] as $where ) {
				if ( !empty( $where ) ) {
					$out .= $where.' AND ';
				}
			}
		}
		elseif ( isset( $this->appli['where'] ) && is_string( $this->appli['where'] ) ) {
			if ( !empty( $this->appli['where'] ) ) {
				$out = $this->appli['where'];
			}
		}

		if ( !empty( $out ) ) {
			$out = ' WHERE '.trim( $out, ' AND ' );
		}

		if ( $out == ' WHERE ' ) {
			$out = '';
		}

		return $out.$this->getJoin();
	}

	private function getOrderby () {
		return ( !empty( $this->appli['orderby'] ) ? ' ORDER BY '.$this->appli['orderby'] : '' );
	}

	private function getLimit () {
		return ( !empty( $this->appli['limit'] ) ? ' LIMIT '.$this->appli['limit'] : '' );
	}

	private function getGroupby () {
		$out = '';

		if ( isset( $this->appli['groupby'] ) && is_array( $this->appli['groupby'] ) ) {
			$this->appli['groupby'] = array_unique( $this->appli['groupby'] );

			$out = ' GROUP BY ';
			foreach ( $this->appli['groupby'] as $groupby ){
				$out .= $groupby.', ';
			}

			$out = ' '.trim( $out, ', ' );
		}
		elseif ( isset( $this->appli['groupby'] ) && is_string( $this->appli['groupby'] ) ) {
			if ( !empty( $this->appli['groupby'] ) ) {
				$out = ' GROUP BY '.$this->appli['groupby'];
			}
		}

		return $out;
	}

	private function getHaving () {
		$out = '';

		if ( isset( $this->appli['having'] ) && is_array( $this->appli['having'] ) ) {
			$this->appli['having'] = array_unique( $this->appli['having'] );

			$out = ' HAVING ';
			foreach ( $this->appli['having'] as $having ){
				$out .= $having.', ';
			}

			$out = ' '.trim( $out, ', ' );
		}
		elseif ( isset( $this->appli['having'] ) && is_string( $this->appli['having'] ) ) {
			if ( !empty( $this->appli['having'] ) ) {
				$out = ' HAVING '.$this->appli['having'];
			}
		}

		return $out;
	}

	public function getQuery ( $appli ) {
		$this->appli = $appli;

		if ( isset( $appli['debug'] ) && $appli['debug'] ) {
			unset( $appli['debug'] );
			echo $this->getQuery( $appli ).'<br />'."\n";

			//mail( 'parweb@gmail.com', 'debug sql', $this->getQuery( $appli )."\n\n\n\n".print_r( debug_backtrace() ) );
		}

		return $this->getSelect().$this->getFrom().$this->getWhere().$this->getGroupby().$this->getHaving().$this->getOrderby().$this->getLimit();
	}

	public function add ( $array ) {
		global $SQL;

		if ( $this->hasDate() ) {
			$array['date'] = 'NOW()';
		}

		if ( count( $array ) > 0 ) {
			$head = '`'.join( '`, `', array_keys( $array ) ).'`';
			$values = "'".join( "', '", array_values( $array ) )."'";

			$values = str_replace( "'NOW()'", 'NOW()', $values );
		}

		$sql = "INSERT INTO ".$this->name()." ( $head ) VALUES( $values )";

		$mysql_query = $SQL->prepare( $sql );

		if ( !is_object( $mysql_query ) ) {
			echo "INSERT INTO ".$this->name()." ( $head ) VALUES( $values )".'<br />';
		}
		else {
			$mysql_query->execute();
		}

		$id = $SQL->lastInsertId();

		$_SESSION['cache'][$this->name] = true;

		return $id;
	}

	public function delete ( $id ) {
		global $SQL;

		$sql = "DELETE FROM ".$this->name()." WHERE ".$this->primary()." = ".(int)$id;
		$mysql_query = $SQL->prepare( $sql );

		$_SESSION['cache'][$this->name] = true;

		return $mysql_query->execute();
	}

	public function delete_where ( $params ) {
		global $SQL;

		if ( is_array( $params ) ) {
			$params = array_unique( $params );

			$out_where = '';
			foreach ( $params as $where ) {
				if ( !empty( $where ) ) {
					$out_where .= $where.' AND ';
				}
			}
		}
		elseif ( is_string( $params ) ) {
			if ( !empty( $params ) ) {
				$out_where = $params;
			}
		}

		if ( !empty( $out_where ) ) {
			$out_where = trim( $out_where, ' AND ' );

			$out_where = ' WHERE '.$out_where;
		}

		if ( $out_where == ' WHERE ' ) {
			$out_where = '';
		}

		$mysql_query = $SQL->prepare( "DELETE FROM ".$this->name()." ".$out_where );
		$mysql_query->execute();

		$_SESSION['cache'][$this->name] = true;
	}

	private function hasDate () {
		foreach ( $this->one as $field ) {
			if ( $field == 'date' ) {
				return true;
			}
		}
	}

	public function save ( $id, $array ) {
		global $SQL;

		if ( count( $array ) > 0 ) {
			$update = 'SET ';

			foreach ($array as $k => $v ) {
				if ( $v === '++' ) {
					$update .= '`'.$k.'` = '.$k.' + 1'.', ';
				}
				elseif ( $v === '--' ) {
					$update .= '`'.$k.'` = '.$k.' - 1'.', ';
				}
				elseif ( preg_match( '#^-#', $v ) || preg_match( '#^\+#', $v ) ) {
					$update .= '`'.$k.'` = '.$k.$v.', ';
				}
				elseif ( $k == 'date' ) {
					$update .= '`'.$k.'` = '.$v.', ';
				}
				else {
					$update .= '`'.$k.'` = \''.$v.'\', ';
				}
			}

			$update = trim( $update, ', ' );
		}

		if ( (int)$id == $id ) {
			$where = ' WHERE '.$this->primary()." = $id";
		}
		elseif ( is_string( $id ) ) {
			$where = " WHERE $id";
		}
		else {
			$where = ' WHERE '.$this->primary()." = $id";
		}
		

		$sql = "UPDATE `".$this->name()."` ".$update." $where";
//echo 'file: '.__FILE__.' ligne: '.__LINE__."<br /><b>\$sql :</b> ".$sql.'<br />';exit;
		$mysql_query = $SQL->prepare( $sql );
		$mysql_query->execute();

		$_SESSION['cache'][$this->name] = true;

		return $id;
	}

	public function verif_exist ( $where, $select = null ) {
		$appli['select'][] = $this->alias.'.'.$this->primary();
		
		if ( $select ) {
			$appli['select'] = array_merge( $appli['select'], $select );
		}
	
		$appli['where'] = $where;
		$appli['limit'] = 1;
		$appli['nocache'] = true;
		//$appli['debug'] = true;

		$object = $this->listes( $appli, 'none' );

		if ( count( $object ) ) {
			$return = $object[0];
		}
		else {
			$return = false;
		}

		return $return;
	}

	public function getPublic () {
		$req = mysql_query( "SHOW FIELDS FROM ".$this->name() );
		while ( $rep = mysql_fetch_assoc( $req ) ) {
			echo 'public $'.$rep['Field'].';<br />';
		}

		exit;
	}

	public function table_existe () {
		$sql = 'SHOW TABLES';

		$sign = DIR.APP.CACHE.md5( 'table_existe:'.$this->name().$sql ).'.php';
		if ( file_exists( $sign ) ) return unserialize( file::get( $sign ) );

		$mysql_query = sql::query( $sql );

		$tables = $mysql_query->fetchAll();

		$return = false;
		foreach ( $tables as $table ) {
			if ( $table[0] == $this->name() ) {
				$return = true;
			}
		}

		file::add( $sign, serialize( $return ) );

		return $return;
	}

	public function create_table ( $params = array() ) {
		if ( $this->sql ) {
			$sql = 'CREATE TABLE `'.$this->name().'` ('."\n";
			foreach ( $this->fields() as $name => $item ) {
				if ( isset( $item['type'] ) ) {
					switch ( $item['type'] ) {
						case 'primary':
							$max = ( isset( $item['validate']['max'] ) ) ? $item['validate']['max'] : 11;
							$sql .= "\t".'`'.$name.'` int('.$max.') NOT NULL AUTO_INCREMENT,'."\n";
						break;

						case 'numeric':
							$max = ( isset( $item['validate']['max'] ) ) ? $item['validate']['max'] : 11;
							$sql .= "\t".'`'.$name.'` INT('.$max.') NOT NULL,'."\n";
						break;

						case 'status':
							$sql .= "\t".'`'.$name.'` TINYINT(2) NOT NULL,'."\n";
						break;

						case 'text':
							$max = ( isset( $item['validate']['max'] ) ) ? $item['validate']['max'] : 255;
							$sql .= "\t".'`'.$name.'` varchar('.$max.') NOT NULL,'."\n";
						break;

						case 'file':
							$max = ( isset( $item['validate']['max'] ) ) ? $item['validate']['max'] : 255;
							$sql .= "\t".'`'.$name.'` varchar('.$max.') NOT NULL,'."\n";
						break;

						case 'textarea':
							$sql .= "\t".'`'.$name.'` text NOT NULL,'."\n";
						break;

						case 'date':
							$sql .= "\t".'`'.$name.'` DATETIME NOT NULL,'."\n";
						break;

						default:
							$max = ( isset( $item['validate']['max'] ) ) ? $item['validate']['max'] : 255;
							$sql .= "\t".'`'.$name.'` varchar('.$max.') NOT NULL,'."\n";
						break;
					}
				}
				else {
					switch ( $name ) {
						case 'date':
							$sql .= "\t".'`'.$name.'` DATETIME NOT NULL,'."\n";
						break;

						case 'status':
							$max = ( isset( $item['validate']['max'] ) ) ? $item['validate']['max'] : 2;
							$sql .= "\t".'`'.$name.'` INT('.$max.') NOT NULL,'."\n";
						break;

						case preg_match( '#_id$#', $name ):
							$max = ( isset( $item['validate']['max'] ) ) ? $item['validate']['max'] : 11;
							$sql .= "\t".'`'.$name.'` INT('.$max.') NOT NULL,'."\n";
						break;

						default:
							$max = ( isset( $item['validate']['max'] ) ) ? $item['validate']['max'] : 255;
							$sql .= "\t".'`'.$name.'` varchar('.$max.') NOT NULL,'."\n";
						break;
					}
				}
			}

			$sql .= "\t".'PRIMARY KEY (`'.$this->primary().'`)'."\n";
			$sql .= ') ENGINE=InnoDB DEFAULT CHARSET=utf8;';

			debug($sql);

			sql::query( $sql );
		}
	}

	public function listes ( $appli = array(), $sortie = 'recursif', $field = '' ) {
		global $SQL, $bdd;

		$sign = DIR.APP.CACHE.$this->name.'_'.md5( 'listes:'.serialize( $appli ).serialize( $sortie ).serialize( $field ) ).'.php';
		if ( file_exists( $sign ) && !isset( $_SESSION['cache'][$this->name] ) && !isset( $appli['nocache'] ) ) {
			$serialize = file::get( $sign );
			return unserialize( $serialize );
		}

		if ( isset( $_SESSION['cache'][$this->name] ) ) {
			unset( $_SESSION['cache'][$this->name] );
		}

		if ( config('bdd.active') ) {
			$this->out = $sortie;

			if ( !isset( $appli['where'] ) ) {
				$appli['where'] = array();
			}

			if ( $this->out == 'first' || $this->out == 'extract' ) {
				$appli['limit'] = 1;
			}

			if ( $this->out == 'count' ) {
				$appli['select'] = $this->alias().'.'.$this->primary();
			}

			$sql = $this->getQuery( $appli );

			$mysql_query = $SQL->prepare( $sql );

			if ( !is_object( $mysql_query ) ) {
				echo 'file: '.__FILE__.' ligne: '.__LINE__."<br /><b>\$sql ($sortie):</b> ".$sql.'<br />';
			}
			else {
				$mysql_query->execute();
			}

			if ( $this->out == 'count' ) {
				$result = $mysql_query->rowCount();

				file::add( $sign, serialize( $result ) );

				return $result;
			}
			elseif ( $this->out == 'pipe' ) {
				if ( mysql_num_rows( $mysql_query ) > 0 ) {
					while ( $lists = mysql_fetch_assoc( $mysql_query ) ) {
						$lists_array[] = $lists[$field];
					}

					file::add( $sign, serialize( array2pipe( $lists_array ) ) );

					return array2pipe( $lists_array );
				}
			}
			elseif ( $this->out == 'extract' ) {
				$liste = $mysql_query->fetchAll( PDO::FETCH_ASSOC );

				if ( !isset( $liste[0] ) && config( 'site.env' ) == 'local' ) {
					debug( $sql );
					debug( $liste );
					debug( $_SESSION );exit;
				}

				file::add( $sign, serialize( $liste[0][$field] ) );

				return $liste[0][$field];
			}
			elseif ( $this->out == 'first' ) {
				$liste = $mysql_query->fetchAll( PDO::FETCH_ASSOC );

				if ( isset( $liste[0] ) ) {
					file::add( $sign, serialize( $liste[0] ) );
					return $liste[0];
				}
			}
			elseif ( $this->out == 'array' && $field != '' ) {
				if ( mysql_num_rows( $mysql_query ) > 0 ) {
					while ( $lists = mysql_fetch_assoc( $mysql_query ) ) {
						$lists_array[] = $lists[$field];
					}

					if ( !count( $lists_array ) ) {
						return array();
					}

					file::add( $sign, serialize( $lists_array ) );

					return $lists_array;
				}
			}
			else {
				//$tuples = $mysql_query->fetchAll( PDO::FETCH_CLASS, ucfirst( $this->name().'Model' ) );
				$tuples = $mysql_query->fetchAll( PDO::FETCH_CLASS );

				if ( !count( $tuples ) ) {
					$tuples = array();
				}

				if ( $this->out == 'recursif' ) {
					foreach ( $tuples as $i => $tuple ) {
						foreach ( $this->belong() as $item ) {
							$class_name = ucfirst( $item ).'Model';
							$Class = new $class_name;

							$current_id = $Class->primary();
							$foreign_field = "{$item}_{$current_id}";

							$tuples[$i]->$item = $Class->listes( array( 'where' => "$current_id = {$tuple->$foreign_field}" ), 'first' );
						}

						foreach ( $this->many() as $k => $item ) {
							$appli = array();

							if ( !is_integer( $k ) ) {
								$pivot = $item;
								$item = $k;

								$appli['from'] = "$pivot $pivot";
								$appli['where'][] = "$pivot.{$item}_id = $item.id";
							}

							$class_name = ucfirst( $item ).'Model';
							$Class = new $class_name;

							$current_id = $this->primary();
							$current_class_name = $this->name();

							$foreign_field = $current_class_name.'_'.$current_id;

							$appli['where'][] = "$foreign_field = {$tuple->$current_id}";
							//$appli['debug'] = true;

							$tuples[$i]->{$item} = $Class->listes( $appli, 'none' );
						}
					}
				}

				$serialize = serialize( $tuples );

				file::add( $sign, $serialize );

				return $tuples;
			}
		}
		else {
			return array();
		}
	}
}