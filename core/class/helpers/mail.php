<?php

class mail {
	static public function send ( $to, $tpl = 'default', $datas = array(), $from = null ) {
		if ( $from ) {
			if ( is_array( $from ) ) {
				$from = "$from[name]<$from[email]>";
			}
		}
		else {
			$from = config( 'mail.contact.name' ).'<'.config( 'mail.contact' ).'>';
		}

		if ( is_string( $datas ) ) {
			$tpl = self::replaceData( 'default', array( 'subject' => $tpl, 'body' => $datas ) );
		}
		else {
			$tpl = self::replaceData( $tpl, $datas );
		}

		$headers = 'From: '.$from."\r\n".
		'Reply-To: '.$from."\r\n".
		'X-Mailer: PHP/'.phpversion();
echo 'file: '.__FILE__.' ligne: '.__LINE__."<br /><b>\$tpl['body'] :</b> ".$tpl['body'].'<br />';
		mail( $to, $tpl['subject'], $tpl['body'], $headers );
	}

	static public function formatData ( array $data ) {
		$keys = $values = array();

		foreach ( $data as $k => $v ) {
			$keys[] = ":$k:";
			$values[] = $v;
		}

		return array( 'keys' => array_values( $keys ), 'values' => array_values( $values ) );
	}

	static public function replaceData ( $tpl, $_data = null ) {
		if ( $_data ) $data = self::formatData( $_data );

		$template = file::get( DIR.APP.TEMPLATE.config( 'view.template' ).DS.MAIL.'default.tpl.php' );

		if ( $tpl == 'default' ) {
			$tpl = $_data;
		}
		else {
			$tpl = include DIR.APP.TEMPLATE.config( 'view.template' ).DS.MAIL.$tpl.'.tpl.php';
	
			$tpl['subject'] = str_replace( $data['keys'], $data['values'], $tpl['subject'] );
			$tpl['body'] = str_replace( $data['keys'], $data['values'], $tpl['body'] );
		}
		
		$tpl['body'] = str_replace( array(':body:', ':subject:'), array($tpl['body'], $tpl['subject']), $template );

		return $tpl;
	}
}