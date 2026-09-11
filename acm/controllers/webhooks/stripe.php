<?php
if (!defined('l2jmobius')) {
    die('Direct access not permitted');
}

\Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;
try {
    $event = \Stripe\Webhook::constructEvent(
        $payload,
        $sig_header,
        $settings->get('stripe_webhook')
    );
} catch (\UnexpectedValueException $e) {
    // Invalid payload
	echo 'invalid payload';
    http_response_code(400);
    exit();
} catch (\Stripe\Exception\SignatureVerificationException $e) {
    // Invalid signature
	echo 'invalid sig';
    http_response_code(400);
    exit();
}

if ($event->type == 'checkout.session.completed') {
    if (isset($event->data->object->client_reference_id)) {
        $donationId = $event->data->object->client_reference_id;
    } else {
		echo 'invalid donation id';
        http_response_code(400);
        exit();
    }
	
	$table_name = 'acm_donations';
	$dbHandler = $db_game;
	if($settings->check('use_balance')){
		$table_name = 'acm_donations_balance';
		$dbHandler = $db;
	}

    $sql = 'SELECT * FROM '.$table_name.' WHERE id = ?';
    $params = array($donationId);
    $row = $dbHandler->row($sql, $params);
    if (isset($row->id)) {
		if($row->status == 1){ 
			//Already paid
			echo 'Already paid';
			http_response_code(400);
			exit();
		}
		if($row->payment_method != 'stripe'){ 
			//Wrong payment method
			echo 'Wrong payment method';
			http_response_code(400);
			exit();
		}
        $data = array(
            'status' => 1,
			'date_paid'=>date('Y-m-d H:i:s')
        );
        $dbHandler->update($table_name, $data, array('id' => $row->id));
		
		if($settings->check('use_balance')){
			$sql = 'SELECT value FROM account_data WHERE account_name = ? AND var = ?';
			$params = array($row->account_name, 'donations_balance');
			$balance = $db->row($sql, $params);
			if(!empty($balance->value))
				$current_balance = floatval($balance->value);
			else
				$current_balance = 0;
			
			$new_balance = $current_balance + floatval($row->amount);
			//Update account's balance
			$db->delete('account_data', array('account_name'=>$row->account_name, 'var'=>'donations_balance'));
			$db->insert('account_data', array('account_name'=>$row->account_name, 'var'=>'donations_balance', 'value'=>$new_balance));
			exit;
		}
		//Send items to player
		$data = array(
			'receiver'=>$row->char_id,
			'subject'=>'Donation',
			'message'=>'Thank you for your donation! Here is your item.',
			'items'=>$row->item_id_to_give.' '.$row->quantity_to_give
		);
		$db_game->insert('custom_mail', $data);
    }
}
