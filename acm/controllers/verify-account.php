<?php
if (!defined('l2jmobius')) {
    die('Direct access not permitted');
}
if(!isset($cParams[1])){
	$_SESSION['alert'] = array(
		'type'=>'danger',
		_('The verification link has expired.')
	);
	header("Location: ".$appURL."/".$language_id."/login");
	exit;
}

$sql = 'SELECT login, accessLevel FROM accounts WHERE login = ?';
$params = array($cParams[0]);
$row = $db->row($sql, $params);
if(!isset($row->login)){
	$_SESSION['alert'] = array(
		'type'=>'danger',
		'message'=>_('The verification link has expired.')
	);
	header("Location: ".$appURL."/".$language_id."/login");
	exit;
}

if($row->accessLevel != ACCOUNT_NOT_VERIFIED){
	$_SESSION['alert'] = array(
		'type'=>'danger',
		'message'=>_('Your account is already verified.')
	);
	header("Location: ".$appURL."/".$language_id."/login");
	exit;
}

$sql = 'SELECT value FROM account_data WHERE account_name = ? AND var = ?';
$params = array($row->login, 'website_key');
$var = $db->row($sql, $params);
if($var->value != $cParams[1]){
	$_SESSION['alert'] = array(
		'type'=>'danger',
		'message'=>_('The verification link has expired.')
	);
	header("Location: ".$appURL."/".$language_id."/login");
	exit;
}

$db->update('accounts', array('accessLevel' => ACCOUNT_USER), array('login'=>$row->login));

$hash = bin2hex(random_bytes(16));
$db->update('account_data', array('value'=>$hash), array('account_name'=>$row->login, 'var'=>'website_key'));

$_SESSION['alert'] = array(
	'type'=>'success',
	'message'=>_('Your account has been verified. You may now login.')
);
header("Location: ".$appURL."/".$language_id."/login");
exit;