<?php
if (!defined('l2jmobius')) {
    die('Direct access not permitted');
}
if(!isset($cParams[1])){
	$_SESSION['alert'] = array(
		'type'=>'danger',
		'message'=>_('The reset link has expired.')
	);
	header("Location: ".$appURL.'/'.$language_id."/login");
	exit;
}

$sql = 'SELECT login, accessLevel FROM accounts WHERE login = ?';
$params = array($cParams[0]);
$row = $db->row($sql, $params);
if(!isset($row->login)){
	$_SESSION['alert'] = array(
		'type'=>'danger',
		'message'=>_('The reset link has expired.')
	);
	header("Location: ".$appURL.'/'.$language_id."/login");
	exit;
}

if($row->accessLevel == ACCOUNT_BANNED){ // -1 banned.
	$_SESSION['alert'] = array(
		'type'=>'danger',
		'message'=>_('Your account is banned.')
	);
	header("Location: ".$appURL.'/'.$language_id."/login");
	exit;
}

$sql = 'SELECT value FROM account_data WHERE account_name = ? AND var = ?';
$params = array($row->login, 'website_key');
$var = $db->row($sql, $params);
if($var->value != $cParams[1]){
	$_SESSION['alert'] = array(
		'type'=>'danger',
		'message'=>_('The reset link has expired.')
	);
	header("Location: ".$appURL.'/'.$language_id."/login");
	exit;
}

if(isset($_POST['password1'])){
	if($_POST['password1'] != $_POST['password2']){
		$_SESSION['alert'] = array(
			'type'=>'danger',
			'message'=>_('Your new passwords do not match.')
		);
		header("Location: ".$appURL.'/'.$language_id."/reset-password/".$cParams[0]."/".$cParams[1]);
		exit;
	}
	elseif(preg_match('/[^a-zA-Z0-9.:+=_!#@$%^&*()\-\/]/', $_POST['password2'])){
		$_SESSION['alert'] = array(
			'type'=>'danger',
			'message'=>_('Your new password must only contain letters from A to Z, numbers from 0 to 9 and the following special characters: . ! @ # $ % ^ & * ( ) - + = _ /')
		);
		header("Location: ".$appURL.'/'.$language_id."/reset-password/".$cParams[0]."/".$cParams[1]);
		exit;
	}
	elseif(strlen($_POST['password2']) < 6 || strlen($_POST['password2']) > 20){
		$_SESSION['alert'] = array(
			'type'=>'danger',
			'message'=>_('Your new password must be between 6 and 20 characters long.')
		);
		header("Location: ".$appURL.'/'.$language_id."/reset-password/".$cParams[0]."/".$cParams[1]);
		exit;
	}
	
	$db->update('accounts', array('password'=>base64_encode(pack("H*", sha1(mb_convert_encoding($_POST['password2'], 'UTF-8', mb_list_encodings()))))), array('login'=>$row->login));
	$_SESSION['alert'] = array(
		'type'=>'success',
		'message'=>_('Your password was changed. You may now login.')
	);
	
	$hash = bin2hex(random_bytes(16));
	$db->update('account_data', array('value'=>$hash), array('account_name'=>$row->login, 'var'=>'website_key'));
	header("Location: ".$appURL.'/'.$language_id."/login");
	exit;
}

if(isset($_SESSION['alert'])){
	$alert = $_SESSION['alert'];
	unset($_SESSION['alert']);
}

$page = array(
    'title' => _('Reset your password')
);