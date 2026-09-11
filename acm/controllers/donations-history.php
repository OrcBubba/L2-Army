<?php
if (!defined('l2jmobius')) {
    die('Direct access not permitted');
}

$page = array(
    'title'=>_('Donations History')
);

$sql = 'SELECT * FROM acm_donations WHERE account_name = ? AND (status = ? OR date_created > ?) ORDER BY id DESC';
$params = array($account->login, 1, date('Y-m-d H:i:s', time() - 300));
$donations = $db_game->fetch($sql, $params);
