<?php
if (!defined('l2jmobius')) {
    die('Direct access not permitted');
}

$page = array(
    'title' => _('Change password')
);

if(isset($_POST['current_password'])) {

	// Block banned accounts from changing their password.
    if($account->accessLevel == ACCOUNT_BANNED){
        $_SESSION['alert'] = array(
            'type'=>'danger',
            'message'=>_('Your account is banned. You cannot change your password.')
        );
        header("Location: ".$appURL."/".$language_id."/login");
        exit;
    }

	if($account->accessLevel >= ACCOUNT_ADMIN && $demoMode){
		$_SESSION['alert'] = array(
			'type'=>'danger',
			'message'=>_('This is a demo mode and you cannot edit admin accounts.')
		);
		header("Location: ".$appURL."/".$language_id."/change-password");
		exit;
	}
	
	if(base64_encode(pack("H*", sha1(mb_convert_encoding($_POST['current_password'], 'UTF-8', mb_list_encodings())))) != $account->password){
		$_SESSION['alert'] = array(
			'type'=>'danger',
			'message'=>_('Your current password is wrong.')
		);
		header("Location: ".$appURL."/".$language_id."/change-password");
		exit;
	}
	elseif($_POST['password1'] != $_POST['password2']){
		$_SESSION['alert'] = array(
			'type'=>'danger',
			'message'=>_('Your new passwords do not match.')
		);
		header("Location: ".$appURL."/".$language_id."/change-password");
		exit;
	}
	elseif(preg_match('/[^a-zA-Z0-9.:+=_!#@$%^&*()\-\/]/', $_POST['password2'])){
		$_SESSION['alert'] = array(
			'type'=>'danger',
			'message'=>_('Your new password must only contain letters from A to Z, numbers from 0 to 9 and the following special characters: . ! @ # $ % ^ & * ( ) - + = _ /')
		);
		header("Location: ".$appURL."/".$language_id."/change-password");
		exit;
	}
	elseif(strlen($_POST['password2']) < 6 || strlen($_POST['password2']) > 20){
		$_SESSION['alert'] = array(
			'type'=>'danger',
			'message'=>_('Your new password must be between 6 and 20 characters long.')
		);
		header("Location: ".$appURL."/".$language_id."/change-password");
		exit;
	}
	else {
		$db->update('accounts', array('password'=>base64_encode(pack("H*", sha1(mb_convert_encoding($_POST['password2'], 'UTF-8', mb_list_encodings()))))), array('login'=>$account->login));
		$_SESSION['alert'] = array(
			'type'=>'success',
			'message'=>_('Your password was changed.')
		);
		header("Location: ".$appURL."/".$language_id."/change-password");
		exit;
	}
}

if(isset($_SESSION['alert'])){
	$alert = $_SESSION['alert'];
	unset($_SESSION['alert']);
}