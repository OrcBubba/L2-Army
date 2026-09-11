<?php
if (!defined('l2jmobius')) {
    die('Direct access not permitted');
}

$page = array(
    'title' => _('Change your email')
);

if(isset($_POST['new_email'])){
	if($account->accessLevel >= ACCOUNT_ADMIN && $demoMode){
		$_SESSION['alert'] = array(
			'type'=>'danger',
			'message'=>_('This is a demo mode and you cannot edit admin accounts.')
		);
		header("Location: ".$appURL."/".$language_id."/change-email");
		exit;
	}
	
	if($account->email == ''){
		$db->update('accounts', array('email'=>$_POST['new_email']), array('login'=>$account->login));
		$_SESSION['alert'] = array(
			'type'=>'success',
			'message'=>_('Your email was changed.')
		);
		header("Location: ".$appURL."/".$language_id."/change-email");
		exit;
	}
	if($account->email == $_POST['new_email']){
		$_SESSION['alert'] = array(
			'type'=>'danger',
			'message'=>_('The email you typed is the same.')
		);
		header("Location: ".$appURL."/".$language_id."/change-email");
		exit;
	}
	$generateCode = true;
	if(isset($_POST['code'])){
		$generateCode = false;
		$sql = 'SELECT value FROM account_data WHERE account_name = ? AND var = ?';
		$params = array($account->login, 'change_email');
		$row = $db->row($sql, $params);
		if(isset($row->value) && $row->value == $_POST['code']){
			$db->update('accounts', array('email'=>$_POST['new_email']), array('login'=>$account->login));
			$_SESSION['alert'] = array(
				'type'=>'success',
				'message'=>('Your email was changed.')
			);
			header("Location: ".$appURL."/".$language_id."/change-email");
			exit;
		}
		$alert = array(
			'type'=>'danger',
			'message'=>_('The code your provided is wrong.').' <a href="'.$appURL.'/'.$language_id.'/change-email">'._('Start over').'</a>'
		);
	}
	if($generateCode){
		$code = '';
		for($i = 0; $i < 6; $i++)
			$code .= rand(0, 9);
		$db->delete('account_data', array('account_name'=>$account->login, 'var'=>'change_email'));
		$db->insert('account_data', array('account_name'=>$account->login, 'var'=>'change_email', 'value'=>$code));
		
		//Send email
		$email_body = file_get_contents($emailTemplates.'change-email.html');
		$email_body = str_replace('{{code}}', $code, $email_body);
		$email_body = str_replace('{{new_email}}', $_POST['new_email'], $email_body);
		if($settings->has('email_logo'))
			$email_body = str_replace('{{logo}}', $settings->get('email_logo'), $email_body);
		else
			$email_body = str_replace('{{logo}}', $cdnURL.'/img/logo.png', $email_body);
		$email_body = str_replace('{{account_name}}', $account->login, $email_body);
		$email_body = str_replace('{{app_name}}', $appName, $email_body);
		$email_body = str_replace('{{year}}', date('Y'), $email_body);
		
		require_once $appClasses.'Email.class.php';
		$email = new Email();
		$email->addSubject('Change the email of your account '.$account->login);
		$email->addBody($email_body);
		$email->addTo($account->email);
		if(!$email->send()){
			$_SESSION['alert'] = array(
				'type'=>'danger',
				'message'=>_('We could not send you an email to verify that you have access to your current email.')
			);
			header("Location: ".$appURL."/".$language_id."/change-email");
			exit;
		}
	}
}

if(isset($_SESSION['alert'])){
	$alert = $_SESSION['alert'];
	unset($_SESSION['alert']);
}