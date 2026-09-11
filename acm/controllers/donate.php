<?php
if (!defined('l2jmobius')) {
    die('Direct access not permitted');
}

$page = array(
    'title' => _('Donate')
);
		
if(!$settings->has('currency')){
	$settings->set('currency', 'EUR');
}

$sql = 'SELECT charId, char_name, level, title, accesslevel, online, classid, lastAccess FROM characters WHERE account_name = ?';
$params = array($account->login);
$characters = $db_game->fetch($sql, $params);

$sql = 'SELECT * FROM acm_donation_items';
$items = $db_game->fetch($sql);

if(isset($cParams[1]) && $cParams[0] == 'success'){
	$ids = explode('-', $cParams[1]);
	if($settings->check('use_balance')) {
		$sql = 'SELECT account_name FROM acm_donations_balance WHERE id = ? AND payment_hash = ?';
		$params = array($ids[0], $ids[1]);
		$row = $db->row($sql, $params);
		if(isset($row->account_name) && $row->account_name == $account->login){
			$_SESSION['alert'] = array(
				'type'=>'success',
				'message'=>_('Thank you very much for your donation. Your balance will be updated automatically as soon as your payment is processed.')
			);
			header("Location: ".$appURL."/".$language_id."/donate");
			exit;
		}
	}
	else {
		$sql = 'SELECT account_name FROM acm_donations WHERE id = ? AND payment_hash = ?';
		$params = array($ids[0], $ids[1]);
		$row = $db_game->row($sql, $params);
		if(isset($row->account_name) && $row->account_name == $account->login){
			$_SESSION['alert'] = array(
				'type'=>'success',
				'message'=>_('Thank you very much for your donation. You will receive the items automatically as soon as your payment is processed.')
			);
			header("Location: ".$appURL."/".$language_id."/donate");
			exit;
		}
	}
}

if($settings->check('use_balance')){
	$payment_methods = [];
	if($settings->check('enable_stripe') && $settings->has('stripe_key') && $settings->has('stripe_webhook') && $_ENV['STRIPE_SECRET_KEY'] != ''){
		$payment_methods['stripe'] = 'Stripe';
	}
	if(($settings->check('enable_paypal') || $settings->get('enable_paypal') == 2) && $settings->has('paypal_email')){
		if($settings->get('enable_paypal') == 2)
			$payment_methods['paypal_sandbox'] = 'Paypal (Sandbox)';
		else
			$payment_methods['paypal'] = 'Paypal';
	}
	
	if(isset($_POST['add_balance'])){
		if(!is_numeric($_POST['add_balance']) || floatval($_POST['add_balance']) != $_POST['add_balance']){
			$_SESSION['alert'] = array(
				'type'=>'danger',
				'message'=>_('The amount your typed is wrong. Please make sure that the amount is a number.')
			);
			header("Location: ".$appURL."/".$language_id."/donate");
			exit;
		}
		
		$data = array(
			'account_name'=>$account->login,
			'date_created'=>date('Y-m-d H:i:s'),
			'date_paid'=>null,
			'payment_method'=>$_POST['payment_method'],
			'amount'=>floatval($_POST['add_balance']),
			'status'=>0,
			'payment_hash'=>bin2hex(random_bytes(16))
		);
		$insertedId = $db->insert('acm_donations_balance', $data);
		
		if($insertedId > 0){
			if($data['payment_method'] == 'stripe'){
				$stripe = new \Stripe\StripeClient($_ENV['STRIPE_SECRET_KEY']);
				$items = array(
					array(
						'price_data' => array(
							'currency' => $settings->get('currency'),
							'unit_amount' => ($data['amount'] * 100),
							'tax_behavior' => 'inclusive',
							'product_data' => array(
								'name' => 'Donation to '.$appName,
							)
						),
						'quantity' => 1
					)
				);

				$checkoutData = [
					'line_items' => $items,
					'mode' => 'payment',
					'client_reference_id' => $insertedId,
					'success_url' => $appURL .'/'.$language_id . '/donate/success/' . $insertedId . '-' . $data['payment_hash'],
					'cancel_url' => $appURL .'/'.$language_id. '/donate'
				];
				if(!empty($account->email))
					$checkoutData['customer_email'] = $account->email;
				
				try {
					$checkout_session = $stripe->checkout->sessions->create($checkoutData);
					if(isset($checkout_session->url)){
						header("Location: ".$checkout_session->url);
						exit;
					}
					else {
						$_SESSION['alert'] = array(
							'type'=>'danger',
							'message'=>_('We could not proccess your information. Please contact an administrator and ask him to check the Stripe integration.')
						);
						header("Location: ".$appURL."/".$language_id."/donate");
						exit;
					}
				}
				catch(Exception $e){
					echo $e->getMessage();
					exit;
					$_SESSION['alert'] = array(
						'type'=>'danger',
						'message'=>_('We could not proccess your information. Please contact an administrator and ask him to check the Stripe integration.')
					);
					header("Location: ".$appURL."/".$language_id."/donate");
					exit;
				}
				
			}
			elseif($data['payment_method'] == 'paypal' || $data['payment_method'] == 'paypal_sandbox'){
				$form_url = 'https://www.paypal.com/donate';
				if($data['payment_method'] == 'paypal_sandbox')
					$form_url = 'https://www.sandbox.paypal.com/donate';
				echo '<form style="display:none" id="paypal-form" action="'.$form_url.'" method="post">
				 <input type="hidden" name="business" value="'.$settings->get('paypal_email').'">
				 <input type="hidden" name="no_recurring" value="0">
				 <input type="hidden" name="item_name" value="Donation to '.$appName.'">
				 <input type="hidden" name="amount" value="'.$data['amount'].'">
				 <input type="hidden" name="quantity" value="1">
				 <input type="hidden" name="currency_code" value="'.$settings->get('currency').'">
				 <input type="hidden" name="return" value="'.$appURL .'/'.$language_id . '/donate/success/' . $insertedId . '-' . $data['payment_hash'].'" />
				 <input type="hidden" name="notify_url" value="'.$appURL.'/en/webhooks/paypal/'.$insertedId.'/'.$data['payment_hash'].'">
				 <input type="hidden" name="cancel_return" value="'.$appURL.'/'.$language_id.'/donate" />
				 <input type="image" name="submit" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" alt="Donate">
				 <img alt="" width="1" height="1" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" >
				</form>
				<script>document.getElementById("paypal-form").submit()</script>
				';
			}
		}
		
	}
}

if(!empty($cParams[0]) && is_numeric($cParams[0]) && Count($characters) > 0){
	foreach($items as $row){
		if($row->id == $cParams[0]){
			$donate = $row;
			if(!isset($payment_methods)){
				$payment_methods = [];
				if($settings->check('enable_stripe') && $settings->has('stripe_key') && $settings->has('stripe_webhook') && $_ENV['STRIPE_SECRET_KEY'] != ''){
					$payment_methods['stripe'] = 'Stripe';
				}
				if(($settings->check('enable_paypal') || $settings->get('enable_paypal') == 2) && $settings->has('paypal_email')){
					if($settings->get('enable_paypal') == 2)
						$payment_methods['paypal_sandbox'] = 'Paypal (Sandbox)';
					else
						$payment_methods['paypal'] = 'Paypal';
				}
			}
				
			if(isset($_POST['donation_id'])){
				foreach($characters as $row){
					if($row->charId == $_POST['character']){
						$character = $row;
						break;
					}
				}
				if(!isset($character->charId) || (!$settings->check('use_balance') && !isset($payment_methods[$_POST['payment_method']]))){
					$_SESSION['alert'] = array(
						'type'=>'danger',
						'message'=>_('Something went wrong.')
					);
					header("Location: ".$appURL."/".$language_id."/donate");
					exit;
				}
				$data = array(
					'char_id'=>$character->charId,
					'char_name'=>$character->char_name,
					'account_name'=>$account->login,
					'date_created'=>date('Y-m-d H:i:s'),
					'date_paid'=>null,
					'donation_id'=>$donate->id,
					'item_name'=>$donate->name,
					'status'=>0,
					'payment_hash'=>bin2hex(random_bytes(16)),
					'price'=>$donate->price,
					'item_id_to_give'=>$donate->item_id
				);
				if($donate->allow_multiple == 1 && isset($_POST['quantity']) && intval($_POST['quantity']) > 1){
					$donate->item_quantity *= intval($_POST['quantity']);
					$data['price'] = round(floatval($data['price'] * intval($_POST['quantity'])), 2);
				}
				$data['item_name'] .= ' (x'.$donate->item_quantity.')';
				$data['quantity_to_give'] = $donate->item_quantity;
				
				if($settings->check('use_balance')){
					print_r($data);
					if($account->balance < $data['price']){
						$_SESSION['alert'] = array(
							'type'=>'danger',
							'message'=>_('You do not have the required balance.')
						);
						header("Location: ".$appURL."/".$language_id."/donate");
						exit;
					}
					$data['status'] = 1;
					$data['payment_method'] = 'Balance';
				}
				else {
					$data['payment_method'] = $_POST['payment_method'];
				}
				
				$insertedId = $db_game->insert('acm_donations', $data);
				
				if($insertedId > 0){
					if($settings->check('use_balance')){
						//Update account's balance
						$new_balance = $account->balance - $data['price'];
						$db->delete('account_data', array('account_name'=>$account->login, 'var'=>'donations_balance'));
						$db->insert('account_data', array('account_name'=>$account->login, 'var'=>'donations_balance', 'value'=>$new_balance));
						
						//Send items to player
						$data = array(
							'receiver'=>$data['char_id'],
							'subject'=>'Donation',
							'message'=>'Thank you for your donation! Here is your item.',
							'items'=>$data['item_id_to_give'].' '.$data['quantity_to_give']
						);
						$db_game->insert('custom_mail', $data);
						
						$_SESSION['alert'] = array(
							'type'=>'success',
							'message'=>_('You will receive your items in-game shortly.')
						);
						header("Location: ".$appURL."/".$language_id."/donate");
						exit;
						exit;
					}
					
					if($data['payment_method'] == 'stripe'){
						$stripe = new \Stripe\StripeClient($_ENV['STRIPE_SECRET_KEY']);
						$items = array(
							array(
								'price_data' => array(
									'currency' => $settings->get('currency'),
									'unit_amount' => ($data['price'] * 100),
									'tax_behavior' => 'inclusive',
									'product_data' => array(
										'name' => $data['item_name'],
									)
								),
								'quantity' => 1
							)
						);

						$checkoutData = [
							'line_items' => $items,
							'mode' => 'payment',
							'client_reference_id' => $insertedId,
							'success_url' => $appURL .'/'.$language_id . '/donate/success/' . $insertedId . '-' . $data['payment_hash'],
							'cancel_url' => $appURL .'/'.$language_id. '/donate/'.$donate->id
						];
						if(!empty($account->email))
							$checkoutData['customer_email'] = $account->email;
						
						try {
							$checkout_session = $stripe->checkout->sessions->create($checkoutData);
							if(isset($checkout_session->url)){
								header("Location: ".$checkout_session->url);
								exit;
							}
							else {
								$_SESSION['alert'] = array(
									'type'=>'danger',
									'message'=>_('We could not proccess your information. Please contact an administrator and ask him to check the Stripe integration.')
								);
								header("Location: ".$appURL."/".$language_id."/donate");
								exit;
							}
						}
						catch(Exception $e){
							echo $e->getMessage();
							exit;
							$_SESSION['alert'] = array(
								'type'=>'danger',
								'message'=>_('We could not proccess your information. Please contact an administrator and ask him to check the Stripe integration.')
							);
							header("Location: ".$appURL."/".$language_id."/donate");
							exit;
						}
						
					}
					elseif($data['payment_method'] == 'paypal' || $data['payment_method'] == 'paypal_sandbox'){
						$form_url = 'https://www.paypal.com/donate';
						if($data['payment_method'] == 'paypal_sandbox')
							$form_url = 'https://www.sandbox.paypal.com/donate';
						echo '<form style="display:none" id="paypal-form" action="'.$form_url.'" method="post">
						 <input type="hidden" name="business" value="'.$settings->get('paypal_email').'">
						 <input type="hidden" name="no_recurring" value="0">
						 <input type="hidden" name="item_name" value="'.$data['item_name'].'">
						 <input type="hidden" name="amount" value="'.$data['price'].'">
						 <input type="hidden" name="quantity" value="1">
						 <input type="hidden" name="currency_code" value="'.$settings->get('currency').'">
						 <input type="hidden" name="item_number" value="'.$donate->id.'">
						 <input type="hidden" name="return" value="'.$appURL .'/'.$language_id . '/donate/success/' . $insertedId . '-' . $data['payment_hash'].'" />
						 <input type="hidden" name="notify_url" value="'.$appURL.'/en/webhooks/paypal/'.$insertedId.'/'.$data['payment_hash'].'">
						 <input type="hidden" name="cancel_return" value="'.$appURL.'/'.$language_id.'/donate/'.$donate->id.'" />
						 <input type="image" name="submit" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" alt="Donate">
						 <img alt="" width="1" height="1" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" >
						</form>
						<script>document.getElementById("paypal-form").submit()</script>
						';
					}
				}
				
			}
			
			
			
			$page['js'] = "<script>
			$('#donate').modal('show');
			
			function updatePrice(){
				let _quantity = parseInt($('#donate-form input[name=\"quantity\"]').val());
				let _unitPrice = parseFloat(".$donate->price.");
				let _finalPrice = parseFloat(_quantity * _unitPrice);
				_finalPrice = Math.round((_finalPrice + Number.EPSILON) * 100) / 100
				let _currency = '".($settings->has('currency') ? $settings->get('currency') : 'EUR')."'
				let _formattedPrice = _finalPrice + '€';
				if(_currency == 'USD'){
					_formattedPrice = '$'+_finalPrice;
				}
				else if(_currency == 'BLR'){
					_formattedPrice = 'Rbls '+_finalPrice;
				}
				else if(_currency == 'RUB'){
					_formattedPrice = _finalPrice + ' ₽';
				}
				$('#donate-form input[name=\"price\"]').val(_formattedPrice);
			}
			
			function changeQuantity(type){
				let _quantity = parseInt($('#donate-form input[name=\"quantity\"]').val());
				let _max = parseInt($('#donate-form input[name=\"quantity\"]').prop('max'));
				if(type == 'increase'){
					if(_quantity >= _max){
						$('.plus-quantity').attr('disabled', true);
						return;
					}
					_quantity++;
				}
				else {
					if(_quantity <= 1){
						$('.minus-quantity').attr('disabled', true);
						return;
					}
					_quantity--;
				}
				$('#donate-form input[name=\"quantity\"]').val(_quantity);
				updatePrice();
				if(_quantity == 1){
					$('.minus-quantity').attr('disabled', true);
				}
				else {
					$('.minus-quantity').attr('disabled', false);
				}
				if(_quantity == _max){
					$('.plus-quantity').attr('disabled', true);
				}
				else {
					$('.plus-quantity').attr('disabled', false);
				}
			}
			</script>";
		}
	}
}

if(isset($_SESSION['alert'])){
	$alert = $_SESSION['alert'];
	unset($_SESSION['alert']);
}