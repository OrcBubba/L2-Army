<?php
if (!defined('l2jmobius')) {
    die('Direct access not permitted');
}
if(!isset($cParams[1])){
	// Invalid payload
    http_response_code(400);
    exit();
}
$table_name = 'acm_donations';
$dbHandler = $db_game;
if($settings->check('use_balance')){
	$table_name = 'acm_donations_balance';
	$dbHandler = $db;
}

$sql = 'SELECT * FROM '.$table_name.' WHERE id = ? AND payment_hash = ?';
$params = array($cParams[0], $cParams[1]);
$row = $dbHandler->row($sql, $params);
if (!isset($row->id)) {
	// Invalid payload
    http_response_code(400);
    exit();
}
if($row->status == 1){ 
	//Already paid
	http_response_code(400);
	exit();
}
if ($_SERVER['REQUEST_METHOD'] != "POST"){
	//No post variables
	http_response_code(400);
	exit();
}

$raw_post_data = file_get_contents('php://input');
$raw_post_array = explode('&', $raw_post_data);
$myPost = array();
foreach ($raw_post_array as $keyval) {
    $keyval = explode ('=', $keyval);
    if (count($keyval) == 2)
        $myPost[$keyval[0]] = urldecode($keyval[1]);
}
$req = 'cmd=_notify-validate';
$get_magic_quotes_exists = false;
if (function_exists('get_magic_quotes_gpc'))
    $get_magic_quotes_exists = true;

foreach ($myPost as $key => $value) {
    if ($get_magic_quotes_exists && get_magic_quotes_gpc() == 1)
        $value = urlencode(stripslashes($value));
    else
        $value = urlencode($value);
    $req .= '&'.$key.'='.$value;
}

if($row->payment_method == 'paypal_sandbox')
	$url = "https://ipnpb.sandbox.paypal.com/cgi-bin/webscr";
elseif($row->payment_method == 'paypal')
	$url = "https://ipnpb.paypal.com/cgi-bin/webscr";
else {
	//Wrong payment method
	http_response_code(400);
	exit();
}

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
$curl_result = curl_exec($ch);
if (curl_errno($ch) != 0){
	// cURL error
    curl_close($ch);
	http_response_code(400);
	exit();
}
curl_close($ch);

// Check that the result verifies
if(!str_contains($curl_result, 'VERIFIED')){
	//Invalud Paypal request
	http_response_code(400);
	exit();
}

$txn_id = isset($_POST['txn_id']) ? $_POST['txn_id'] : null;
$amount = isset($_POST['mc_gross']) ? floatval($_POST['mc_gross']) : null;

if($settings->check('use_balance')){
	if($amount != floatval($row->amount)){
		//Invalid amount
		http_response_code(400);
		exit();
	}
}
else{
	if($amount != floatval($row->price)){
		//Invalid amount
		http_response_code(400);
		exit();
	}
}

if(empty($txn_id)){
	http_response_code(400);
	exit();
}
$sql = 'SELECT COUNT(*) as total FROM '.$table_name.' WHERE tnx_id = ?';
$params = array($txn_id);
$total = $dbHandler->row($sql, $params);
if($total->total > 0){
	//Already processed
	http_response_code(400);
	exit();
}

$data = array(
	'status' => 1,
	'date_paid'=>date('Y-m-d H:i:s'),
	'tnx_id'=>$txn_id
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
