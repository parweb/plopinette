<?php

class sql {
	static public function query ( $sql ) {
		global $SQL;

		$mysql_query = $SQL->prepare( $sql );
		$mysql_query->execute();

		return $mysql_query;
	}

	static public function fields ( $table ) {
		global $SQL;

		$stmt = $SQL->query( "SELECT * FROM $table" );

		$i = 0;

		$fields = array();
		while ( $column = $stmt->getColumnMeta( $i++ ) ) {
			$fields[] = $column['name'];
		}

		return $fields;
	}

	static public function getInstance () {
		// Connexion à la base de donnée
		$env = config('site.env');
		$bdd = config( "bdd.$env" );

		if ( config( 'bdd.active' ) ) {
			switch ( config( "bdd.$env.type" ) ) {
				case 'mysql':
					$dsn = "mysql:dbname=$bdd[base];host=$bdd[host];port=$bdd[port]";

					try {
					    $SQL = new PDO( $dsn, $bdd['user'], $bdd['mdp'] );
					}
					catch ( PDOException $e ) {
					    echo 'Connexion échouée : '.$e->getMessage();
						echo "<p>$dsn, $bdd[user], $bdd[mdp]</p>";
					}
				break;

				case 'sqlite':
					$dsn = 'sqlite:'.DIR.APP.SQL.$bdd['path'];

					try {
					    $SQL = new PDO( $dsn );
					}
					catch ( PDOException $e ) {
					    echo 'Connexion échouée : '.$e->getMessage();
						echo "<p>$dsn</p>";
					}
				break;
			}
		}

		return $SQL;
	}
}