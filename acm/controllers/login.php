<?php
if (!defined('l2jmobius')) {
	die('Direct access not permitted');
}
if (isset($cParams[0]) && $cParams[0] == 'logout') {
	if (isset($_COOKIE['rememberme'])) {
		unset($_COOKIE['rememberme']);
		setcookie('rememberme', '', time() - 3600, '/', '', true, true);
	}
	if (isset($_SESSION['account'])) {
		unset($_SESSION['account']);
		header("Location: " . $appURL . "/" . $language_id . "/login");
		exit();
	}
}
if (isset($account->login)) {
	header("Location: " . $appURL . "/" . $language_id);
	exit();
} elseif (isset($_COOKIE['rememberme'])) {
	$cookie = base64_decode($_COOKIE['rememberme']);
	$cookie = explode('-', $cookie);
	$sql = 'SELECT login FROM accounts WHERE login = ?';
	$params = array($cookie[0]);
	$row = $db->row($sql, $params);
	if (isset($row->login)) {
		$sql = 'SELECT value FROM account_data WHERE account_name = ? AND var = ?';
		$params = array($row->login, 'website_key');
		$var = $db->row($sql, $params);
		if (isset($var->value) && $var->value == $cookie[1]) {
			$_SESSION['account'] = $row->login;
			$db->insert('account_login_history', array('account' => $row->login, 'ip' => $user_ip, 'login_date' => date('Y-m-d H:i:s'), 'is_game' => 0));
		}
		header("Location: " . $appURL . "/" . $language_id);
		exit;
	}
	unset($_COOKIE['rememberme']);
	setcookie('rememberme', '', time() - 3600, '/', '', true, true);
}
if (isset($_POST['login_name'])) {
	header('Content-Type: application/json; charset=utf-8');
	$response = array('success' => false, 'error' => _('Something went wrong.'));
	if (isset($_SESSION['wrong_login_attempts'])) {
		foreach ($_SESSION['wrong_login_attempts'] as $key => $date) {
			if ($date < date('Y-m-d H:i:s', (time() - 1800)))
				unset($_SESSION[$key]);
		}
		if (Count($_SESSION['wrong_login_attempts']) > 9) {
			$response['error'] = _('You have tried to login too many times. Try again in a few minutes.');
			echo json_encode($response);
			exit;
		}
	}
	$sql = 'SELECT login, password, accessLevel, email FROM accounts WHERE login = ? AND password = ?';
	$params = array($_POST['login_name'], base64_encode(pack("H*", sha1(mb_convert_encoding($_POST['password'], 'UTF-8', mb_list_encodings())))));
	$row = $db->row($sql, $params);
	if (!isset($row->login)) {
		if (!isset($_SESSION['wrong_login_attempts']))
			$_SESSION['wrong_login_attempts'] = array(date('Y-m-d H:i:s'));
		else
			$_SESSION['wrong_login_attempts'][] = date('Y-m-d H:i:s');
		$response['error'] = _('The account name or password is wrong.');
		echo json_encode($response);
		exit;
	}
	if ($row->accessLevel == ACCOUNT_NOT_VERIFIED) {
		//Verify account
		if (empty($row->email)) {
			$response['error'] = _('Your account is not verified, but it is not linked to an email. Please contact an Administrator.');
			echo json_encode($response);
			exit;
		}
		$sql = 'SELECT value FROM account_data WHERE account_name = ? AND var = ?';
		$params = array($row->login, 'website_key');
		$var = $db->row($sql, $params);
		if (!empty($var->value)) {
			$verification_link = $appURL . '/' . $language_id . '/verify-account/' . $row->login . '/' . $var->value;
		} else {
			$hash = bin2hex(random_bytes(16));
			$verification_link = $appURL . '/' . $language_id . '/verify-account/' . $row->login . '/' . $hash;
			$db->insert('account_data', array(
				'account_name' => $row->login,
				'var' => 'website_key',
				'value' => $hash
			));
		}
		$email_body = file_get_contents($emailTemplates . 'verify-account.html');
		$email_body = str_replace('{{link}}', $verification_link, $email_body);
		if ($settings->has('email_logo'))
			$email_body = str_replace('{{logo}}', $settings->get('email_logo'), $email_body);
		else
			$email_body = str_replace('{{logo}}', $cdnURL . '/img/logo.png', $email_body);
		$email_body = str_replace('{{account_name}}', $row->login, $email_body);
		$email_body = str_replace('{{app_name}}', $appName, $email_body);
		$email_body = str_replace('{{year}}', date('Y'), $email_body);

		require_once $appClasses . 'Email.class.php';
		$email = new Email();
		$email->addSubject(sprintf(_('Verify your account %s'), $row->login));
		$email->addBody($email_body);
		$email->addTo($row->email);
		if ($email->send()) {
			$response['error'] = _('Your account is not verified. We sent you an email with instructions.');
			echo json_encode($response);
			exit;
		}
		$response['error'] = _('Your account is not verified. Please check your inbox to find the verification email.');
		echo json_encode($response);
		exit;
	} elseif ($row->accessLevel == ACCOUNT_BANNED) {
		//Banned
		$response['error'] = _('Your account is banned.');
		echo json_encode($response);
		exit;
	}
	if (isset($_POST['rememberme'])) {
		$sql = 'SELECT value FROM account_data WHERE account_name = ? AND var = ?';
		$params = array($row->login, 'website_key');
		$var = $db->row($sql, $params);
		if (!empty($var->value)) {
			$cookie = $row->login . '-' . $var->value;
		} else {
			$hash = bin2hex(random_bytes(16));
			$db->insert('account_data', array(
				'account_name' => $row->login,
				'var' => 'website_key',
				'value' => $hash
			));
			$cookie = $row->login . '-' . $hash;
		}
		$cookie = base64_encode($cookie);
		setcookie("rememberme", $cookie, [
			'expires' => time() + 2592000,
			'path' => '/',
			'secure' => true,
			'httponly' => true,
			'samesite' => 'Strict'
		]);
	}
	$db->insert('account_login_history', array('account' => $row->login, 'ip' => $user_ip, 'login_date' => date('Y-m-d H:i:s'), 'is_game' => 0));
	if (isset($_SESSION['wrong_login_attempts']))
		unset($_SESSION['wrong_login_attempts']);
	$_SESSION['account'] = $row->login;
	$response['success'] = true;
	echo json_encode($response);
	exit;
} elseif (isset($_POST['register_name'])) {
	header('Content-Type: application/json; charset=utf-8');
	$response = array('success' => false, 'error' => 'Something went wrong.');
	if ($settings->check('disable_registration')) {
		$response['error'] = _('Registrations are currently closed.');
		echo json_encode($response);
		exit;
	}
	if (preg_match('/[^a-zA-Z0-9]/', $_POST['register_name'])) {
		$response['error'] = _('Your account\'s name must only contain letters from A to Z and numbers from 0 to 9.');
		echo json_encode($response);
		exit;
	} elseif (strlen($_POST['register_name']) < 4 || strlen($_POST['register_name']) > 14) {
		$response['error'] = _('Your account\'s name must be between 4 and 14 characters long.');
		echo json_encode($response);
		exit;
	} elseif ($_POST['password1'] != $_POST['password2']) {
		$response['error'] = _('Your passwords do not match. Type the same password twice.');
		echo json_encode($response);
		exit;
	} elseif (preg_match('/[^a-zA-Z0-9.:+=_!#@$%^&*()\-\/]/', $_POST['password1'])) {
		$response['error'] = _('Your password must only contain letters from A to Z, numbers from 0 to 9 and the following special characters: . ! @ # $ % ^ & * ( ) - + = _ /');
		echo json_encode($response);
		exit;
	} elseif (strlen($_POST['password1']) < 6 || strlen($_POST['password1']) > 20) {
		$response['error'] = _('Your password must be between 6 and 20 characters long.');
		echo json_encode($response);
		exit;
	} elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		$response['error'] = _('The provided e-mail address is incorrect.');
		echo json_encode($response);
		exit;
	}
	$sql = 'SELECT login FROM accounts WHERE login = ? ';
	$params = array($_POST['register_name']);
	$row = $db->row($sql, $params);
	if (isset($row->login)) {
		$response['error'] = _('This account name already exists.');
		echo json_encode($response);
		exit;
	}
	$data = array(
		'login' => strtolower($_POST['register_name']),
		'password' => base64_encode(pack("H*", sha1(mb_convert_encoding($_POST['password1'], 'UTF-8', mb_list_encodings())))),
		'email' => $_POST['email'],
		'created_time' => date('Y-m-d H:i:s'),
		'lastactive' => 0,
		'accessLevel' => ACCOUNT_USER, // 0 normal user.
		'lastIP' => $user_ip,
		'lastServer' => 1
	);
	if ($settings->check('require_verification')) {
		$data['accessLevel'] = ACCOUNT_NOT_VERIFIED; // -2 not verified.

		$db->insert('accounts', $data);

		$hash = bin2hex(random_bytes(16));
		$verification_link = $appURL . '/' . $language_id . '/verify-account/' . $data['login'] . '/' . $hash;
		$db->insert('account_data', array(
			'account_name' => $data['login'],
			'var' => 'website_key',
			'value' => $hash
		));

		$email_body = file_get_contents($emailTemplates . 'verify-account.html');
		$email_body = str_replace('{{link}}', $verification_link, $email_body);
		if ($settings->has('email_logo'))
			$email_body = str_replace('{{logo}}', $settings->get('email_logo'), $email_body);
		else
			$email_body = str_replace('{{logo}}', $cdnURL . '/img/logo.png', $email_body);
		$email_body = str_replace('{{account_name}}', $data['login'], $email_body);
		$email_body = str_replace('{{app_name}}', $appName, $email_body);
		$email_body = str_replace('{{year}}', date('Y'), $email_body);

		require_once $appClasses . 'Email.class.php';
		$email = new Email();
		$email->addSubject('Verify your account ' . $data['login']);
		$email->addBody($email_body);
		$email->addTo($_POST['email']);
		if ($email->send()) {
			$response['success'] = true;
			echo json_encode($response);
			exit;
		} else {
			echo json_encode($response);
			exit;
		}
	}

	$db->insert('accounts', $data);
	$db->insert('account_login_history', array('account' => $data['login'], 'ip' => $user_ip, 'login_date' => date('Y-m-d H:i:s'), 'is_game' => 0));
	$_SESSION['account'] = $data['login'];
	$response['success'] = true;
	echo json_encode($response);
	exit;
} elseif (isset($_POST['reset_password'])) {
	header('Content-Type: application/json; charset=utf-8');
	$response = array('success' => false, 'error' => 'Something went wrong.');

	$sql = 'SELECT login, email, accessLevel FROM accounts WHERE login = ? ';
	$params = array($_POST['reset_password']);
	$row = $db->row($sql, $params);
	if (!isset($row->login)) {
		$response['error'] = _('This account does not exist.');
		echo json_encode($response);
		exit;
	}
	if (empty($row->email)) {
		$response['error'] = _('This account is not associated with an email.');
		echo json_encode($response);
		exit;
	}
	if ($row->accessLevel < ACCOUNT_BANNED) {
		$response['error'] = _('This account is banned.');
		echo json_encode($response);
		exit;
	}

	$sql = 'SELECT value FROM account_data WHERE account_name = ? AND var = ?';
	$params = array($row->login, 'website_key');
	$var = $db->row($sql, $params);
	if (!empty($var->value)) {
		$reset_link = $appURL . '/' . $language_id . '/reset-password/' . $row->login . '/' . $var->value;
	} else {
		$hash = bin2hex(random_bytes(16));
		$reset_link = $appURL . '/' . $language_id . '/reset-password/' . $row->login . '/' . $hash;
		$db->insert('account_data', array(
			'account_name' => $row->login,
			'var' => 'website_key',
			'value' => $hash
		));
	}



	$email_body = file_get_contents($emailTemplates . 'reset-password.html');
	$email_body = str_replace('{{link}}', $reset_link, $email_body);
	if ($settings->has('email_logo'))
		$email_body = str_replace('{{logo}}', $settings->get('email_logo'), $email_body);
	else
		$email_body = str_replace('{{logo}}', $cdnURL . '/img/logo.png', $email_body);
	$email_body = str_replace('{{account_name}}', $row->login, $email_body);
	$email_body = str_replace('{{app_name}}', $appName, $email_body);
	$email_body = str_replace('{{year}}', date('Y'), $email_body);

	require_once $appClasses . 'Email.class.php';
	$email = new Email();
	$email->addSubject(sprintf(_('Reset your password for account %s'), $row->login));
	$email->addBody($email_body);
	$email->addTo($row->email);
	if ($email->send()) {
		$response['success'] = true;
		echo json_encode($response);
		exit;
	}
	echo json_encode($response);
	exit;
}

if (isset($_SESSION['alert'])) {
	$alert = $_SESSION['alert'];
	unset($_SESSION['alert']);
}

$page = array(
	'title' => sprintf(_('Login to %s'), $appName)
);
