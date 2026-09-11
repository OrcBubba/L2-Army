<?php
if (!defined('l2jmobius')) {
    die('Direct access not permitted');
}

$page = array(
    'title'=>_('Characters')
);

if(isset($_GET['unstuck'])) {
	$sql = 'SELECT charId, char_name, online, accesslevel, level FROM characters WHERE charId = ? AND account_name = ?';
	$params = array($_GET['unstuck'], $account->login);
	$character = $db_game->row($sql, $params);

	// Check if character exists.
	if(!isset($character->charId)){
		$_SESSION['alert'] = array(
			'type'=>'danger',
			'message'=>_('Character does not exist.')
		);
		header("Location: ".$appURL."/".$language_id);
		exit;
	}

	// Check if account is banned.
    if($account->accessLevel == ACCOUNT_BANNED){
        $_SESSION['alert'] = array(
            'type'=>'danger',
            'message'=>_('Your account is banned. You cannot use this function.')
        );
        header("Location: ".$appURL."/".$language_id);
        exit;
    }

    // Check if character is banned.
    if($character->accesslevel < 0){
        $_SESSION['alert'] = array(
            'type'=>'danger',
            'message'=>_('This character is banned. You cannot use this function.')
        );
        header("Location: ".$appURL."/".$language_id);
        exit;
    }

	// check if character is online.
	if($character->online == 1) {
		$_SESSION['alert'] = array(
			'type'=>'danger',
			'message'=>_('Your character is online. You must first log it out of the game.')
		);
		header("Location: ".$appURL."/".$language_id);
		exit;
	}

	// Check for minimum level.
	if($character->level < 20){
		$_SESSION['alert'] = array(
			'type'=>'danger',
			'message'=>_('You must be at least level 20 to use this function.')
		);
		header("Location: ".$appURL."/".$language_id);
		exit;
	}
	
	$sql = 'SELECT val FROM character_variables WHERE charId = ? AND var = ?';
	$params = array($character->charId, 'last_unstuck');
	$last_unstuck = $db_game->row($sql, $params);
	
	if(isset($last_unstuck->val) && $last_unstuck->val > date('Y-m-d H:i:s', intval(time() - 1800))){
		$_SESSION['alert'] = array(
			'type'=>'danger',
			'message'=>_('You can only use this function once every 30 minutes.')
		);
		header("Location: ".$appURL."/".$language_id);
		exit;
	}

	
	$db_game->update('characters', array('x'=>'83644', 'y'=>'149446', 'z'=>'-3400'), array('charId'=>$character->charId));
	
	if(isset($last_unstuck->val)){
		$db_game->update('character_variables', array('val'=>date('Y-m-d H:i:s')), array('charId'=>$character->charId, 'var'=>'last_unstuck'));
	}
	else {
		$db_game->insert('character_variables', array('charId'=>$character->charId, 'var'=>'last_unstuck', 'val'=>date('Y-m-d H:i:s')));
	}
	
	$_SESSION['alert'] = array(
		'type'=>'success',
		'message'=>_('Your character has been teleported to Giran Castle Town.')
	);
	header("Location: ".$appURL."/".$language_id);
	exit;
}

$sql = 'SELECT charId, char_name, level, title, accesslevel, online, classid, lastAccess FROM characters WHERE account_name = ?';
$params = array($account->login);
$characters = $db_game->fetch($sql, $params);

if(isset($_SESSION['alert'])){
	$alert = $_SESSION['alert'];
	unset($_SESSION['alert']);
}

require_once $appHelpers . 'game-classes.php';