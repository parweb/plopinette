<?php

class PaypalControler extends AppControler {
	public function before () {
		$this->breadcrump( 'Paypal' );
	}

	public function after () {
		//
	}

	public function notifyAction () {
		$email_account = config( 'paypal.mail' );
		$req = 'cmd=_notify-validate';

		foreach ( $_POST as $key => $value ) {
		    $value = urlencode( stripslashes( $value ) );
		    $req .= "&$key=$value";
		}

		$item_name = $_POST['item_name'];
		$item_number = $_POST['item_number'];
		$payment_status = $_POST['payment_status'];
		$payment_amount = $_POST['mc_gross'];
		$payment_tax = $_POST['tax'];
		$payment_ht = $payment_amount - $payment_tax;
		$payment_currency = $_POST['mc_currency'];
		$paypal_id = $_POST['txn_id'];
		$receiver_email = $_POST['receiver_email'];
		$payer_email = $_POST['payer_email'];

		parse_str( $_POST['custom'], $custom );

		file::log( 'paypal', $_POST );

        // vérifier que payment_status a la valeur Completed
        if ( $payment_status == 'Completed' ) {
			if ( $email_account == $receiver_email ) {
				if ( $custom['action'] == 'subscribe' ) {
					$duration = $custom['duration'];
					$uid = $custom['uid'];

					if ( $payment_ht = config( "subscription.prices.$duration" ) ) {
						$sponsor = ( user::sponsor( $uid ) ) ? 1 : 0;

						$Transaction = new TransactionModel;
						$Transaction->add( array(
							'price' => $payment_ht,
							'tax' => $payment_tax,
							'paypal_id' => $paypal_id,
							'user_id' => $uid,
							'action' => 'subscribe',
							'amount' => $duration,
							'status' => 1,
							'sponsor' => $sponsor
						));

						$User = new UserModel( $uid );

						if ( $User->premium() ) {
							$date = strtotime( $User->end_premium );
						}
						else {
							$date = time();
						}

						$date = date( 'Y-m-d H:i:s', strtotime( "+$duration month", $date ) );

						$User->save( $uid, array(
							'end_premium' => $date
						));
					}
					else {
						file::log( 'paypal', "Payment $duration mois = $payment_ht, ne correspond pas !" );
					}
				}
			}
        }
        else {
			file::log( 'paypal', "ERREUR : payment_status -> ( 'Completed' != $payment_status )" );
        }
	}

	public function successAction () {
		user::need( 'member' );

		$this->view( 'title', 'Le payment s\'est correctement effectué !' );
		$this->breadcrump( 'Success' );
	}

	public function cancelAction () {
		//
	}
}