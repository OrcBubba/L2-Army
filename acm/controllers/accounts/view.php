<?php
if (!defined('l2jmobius')) {
    die('Direct access not permitted');
}

if(!$account->isAdmin){
	header("Location: ".$appURL."/".$language_id);
	exit;
}

if(!isset($cParams[0])){
	header("Location: ".$appURL."/".$language_id."/accounts");
	exit;
}

$sql = 'SELECT * FROM accounts WHERE login = ?';
$params = array($cParams[0]);
$row = $db->row($sql, $params);
if(!isset($row->login)){
	$_SESSION['alert'] = array(
		'type'=>'danger',
		'message'=>sprintf(_('Account %s was not found.'), '<strong>'.$row->login.'</strong>')
	);
	header("Location: ".$appURL."/".$language_id."/accounts");
	exit;
}

if($settings->check('use_balance')){
	$row->balance = 0;
	$sql = 'SELECT value FROM account_data WHERE account_name = ? AND var = ?';
	$params = array($row->login, 'donations_balance');
	$bal = $db->row($sql, $params);
	if(!empty($bal->value))
		$row->balance = floatval($bal->value);
}

if(isset($_POST['new_password'])){
	if($row->accessLevel >= ACCOUNT_ADMIN && $demoMode){
		$_SESSION['alert'] = array(
			'type'=>'danger',
			'message'=>_('This is a demo mode and you cannot edit admin accounts.')
		);
		header("Location: ".$appURL."/".$language_id."/accounts/view/".$row->login);
		exit;
	}
	
	if(preg_match('/[^a-zA-Z0-9.:+=_!#@$%^&*()\-\/]/', $_POST['new_password'])){
		$_SESSION['alert'] = array(
			'type'=>'danger',
			'message'=>_('The new password must only contain letters from A to Z, numbers from 0 to 9 and the following special characters: . ! @ # $ % ^ & * ( ) - + = _ /')
		);
		header("Location: ".$appURL."/".$language_id."/accounts/view/".$row->login);
		exit;
	}
	elseif(strlen($_POST['new_password']) < 6 || strlen($_POST['new_password']) > 20){
		$_SESSION['alert'] = array(
			'type'=>'danger',
			'message'=>_('The new password must be between 6 and 20 characters long.')
		);
		header("Location: ".$appURL."/".$language_id."/accounts/view/".$row->login);
		exit;
	}
	else {
		$db->update('accounts', array('password'=>base64_encode(pack("H*", sha1(mb_convert_encoding($_POST['new_password'], 'UTF-8', mb_list_encodings()))))), array('login'=>$row->login));
		$_SESSION['alert'] = array(
			'type'=>'success',
			'message'=>_('The account\'s password was changed.')
		);
		header("Location: ".$appURL."/".$language_id."/accounts/view/".$row->login);
		exit;
	}
}
if(isset($_POST['new_email'])){
	if($row->accessLevel >= ACCOUNT_ADMIN && $demoMode){
		$_SESSION['alert'] = array(
			'type'=>'danger',
			'message'=>_('This is a demo mode and you cannot edit admin accounts.')
		);
		header("Location: ".$appURL."/".$language_id."/accounts/view/".$row->login);
		exit;
	}
	
	if($row->email == $_POST['new_email']){
		$_SESSION['alert'] = array(
			'type'=>'danger',
			'message'=>_('The email you typed is the same.')
		);
		header("Location: ".$appURL."/".$language_id."/accounts/view/".$row->login);
		exit;
	}
	
	$db->update('accounts', array('email'=>$_POST['new_email']), array('login'=>$row->login));
	$_SESSION['alert'] = array(
		'type'=>'success',
		'message'=>_('The account\'s email was changed.')
	);
	header("Location: ".$appURL."/".$language_id."/accounts/view/".$row->login);
	exit;
}
if(isset($_POST['new_access'])){
	if($row->accessLevel >= ACCOUNT_ADMIN && $demoMode){
		$_SESSION['alert'] = array(
			'type'=>'danger',
			'message'=>_('This is a demo mode and you cannot edit admin accounts.')
		);
		header("Location: ".$appURL."/".$language_id."/accounts/view/".$row->login);
		exit;
	}
	
	$db->update('accounts', array('accessLevel'=>$_POST['new_access']), array('login'=>$row->login));
	$_SESSION['alert'] = array(
		'type'=>'success',
		'message'=>_('The account\'s access level was changed.')
	);
	header("Location: ".$appURL."/".$language_id."/accounts/view/".$row->login);
	exit;
}
if(isset($_POST['new_balance'])){
	$db->delete('account_data', array('account_name'=>$row->login, 'var'=>'donations_balance'));
	$db->insert('account_data', array('account_name'=>$row->login, 'var'=>'donations_balance', 'value'=>floatval($_POST['new_balance'])));
	$_SESSION['alert'] = array(
		'type'=>'success',
		'message'=>_('The account\'s balance was changed.')
	);
	header("Location: ".$appURL."/".$language_id."/accounts/view/".$row->login);
	exit;
}


$sql = 'SELECT charId, char_name, level, title, accesslevel, online, classid, lastAccess FROM characters WHERE account_name = ?';
$params = array($row->login);
$characters = $db_game->fetch($sql, $params);
require_once $appHelpers . 'game-classes.php';

$sql = 'SELECT * FROM acm_donations WHERE account_name = ? AND (status = ? OR date_created > ?) ORDER BY id DESC';
$params = array($row->login, 1, date('Y-m-d H:i:s', time() - 300));
$donations = $db_game->fetch($sql, $params);

$sql = 'SELECT ip, login_date, is_game FROM account_login_history WHERE account = ? ORDER BY id DESC';
$params = array($row->login);
$history = $db->fetch($sql, $params);


//Accounts with the same IP
$account_names = [];
$ips = array();
foreach($history as $r){
	if(!in_array($r->ip, $ips))
		$ips[] = $r->ip;
}

if(Count($ips) > 0){
	//First we will search for any other account using the same IP.
	$sql = 'SELECT DISTINCT account FROM account_login_history WHERE ';
	$params = [];
	$i = 0;
	foreach($ips as $ip){
		if($i > 0){
			$sql .= ' OR ';
		}
		$sql .= 'ip = ?';
		$params[] = $ip;
		$i++;
	}
	$rs = $db->fetch($sql, $params);
	
	$account_names = [];
	foreach($rs as $r)
		$account_names[] = $r->account;
	
	//Now we will find all IPs from all of these accounts
	$sql = 'SELECT DISTINCT ip FROM account_login_history WHERE ';
	$params = [];
	$i = 0;
	foreach($ips as $ip){
		if($i > 0){
			$sql .= ' OR ';
		}
		$sql .= 'account = ?';
		$params[] = $ip;
		$i++;
	}
	$rs = $db->fetch($sql, $params);
	
	//Reset the IPs with the new data
	$new_ips = [];
	foreach($rs as $r)
		$new_ips[] = $r->ip;
		
	//Finally, get the final list of accounts.
	if(Count($new_ips) > $ips){
		$sql = 'SELECT DISTINCT account FROM account_login_history WHERE ';
		$params = [];
		$i = 0;
		foreach($new_ips as $ip){
			if($i > 0){
				$sql .= ' OR ';
			}
			$sql .= 'ip = ?';
			$params[] = $ip;
			$i++;
		}
		$rs = $db->fetch($sql, $params);
		
		$account_names = [];
		foreach($rs as $r)
			$account_names[] = $r->account;
	}
}


$page = array(
    'title'=>$row->login.' - ' . _('Game accounts')
);

if(isset($_SESSION['alert'])){
	$alert = $_SESSION['alert'];
	unset($_SESSION['alert']);
}

