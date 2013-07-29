<?php

class TransactionModel extends CoreModel {
	public $fields = array(
		'price' => array(
			'type' => 'numeric',
			'validate' => array()
		),
		'tax' => array(
			'type' => 'numeric',
			'validate' => array()
		),
		'paypal_id' => array(
			'type' => 'numeric',
			'validate' => array()
		),
		'user_id' => array(
			'type' => 'numeric',
			'validate' => array()
		),
		'action' => array(
			'type' => 'text',
			'validate' => array(
				'max' => 255
			)
		),
		'amount' => array(
			'type' => 'numeric',
			'validate' => array()
		),
		'sponsor' => array(
			'type' => 'numeric',
			'validate' => array()
		)
	);

	public $one = array( 'date', 'status' );

	public function requestPaypal ( $price, $name, $custom ) {
		$request = array(
			'METHOD' => 'BMCreateButton',
			'version' => '87',
			'USER' => config( 'paypal.USER' ),
			'PWD' => config( 'paypal.PWD' ),
			'SIGNATURE' => config( 'paypal.SIGNATURE' ),
			'BUTTONCODE' => 'HOSTED',
			'BUTTONTYPE' => 'BUYNOW',
			'BUTTONSUBTYPE' => 'SERVICES',
			'L_BUTTONVAR0' => 'business='.config( 'paypal.mail' ),
			'L_BUTTONVAR1' => "item_name=$name",
			'L_BUTTONVAR2' => "amount=$price",
			'L_BUTTONVAR3' => 'currency_code=EUR',
			'L_BUTTONVAR4' => 'no_note=1',
			'L_BUTTONVAR5' => 'notify_url='.url.link::href( '/paypal/notify/' ),
			'L_BUTTONVAR6' => 'return='.url.link::href( '/paypal/success/' ),
			'L_BUTTONVAR7' => 'cancel='.url.link::href( '/paypal/cancel/' ),
			'L_BUTTONVAR8' => "custom=$custom"
		);

		$request = http_build_query( $request );

		$curlOptions = array(
			CURLOPT_URL => 'https://api-3t.'.config('paypal.sandbox').'paypal.com/nvp',
			CURLOPT_VERBOSE => true,
			CURLOPT_SSL_VERIFYPEER => true,
			CURLOPT_SSL_VERIFYHOST => true,
			CURLOPT_CAINFO => DIR.APP.CONFIG.'cacert.pem',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS => $request
		);

		$ch = curl_init();

		curl_setopt_array( $ch, $curlOptions );

		$response = curl_exec( $ch );

		if ( curl_errno( $ch ) ) {
			return false;
		}
		else {
			curl_close( $ch );
			parse_str( $response, $responseArray );

			return $responseArray['EMAILLINK'];
		}
	}
}