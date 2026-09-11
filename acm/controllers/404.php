<?php
if (!defined('l2jmobius')) {
    die('Direct access not permitted');
}

$page = array(
    'title' => _('Page not found')
);

$backURL = $appURL.'/'.$language_id;
if(isset($_SERVER['HTTP_REFERER'])) {
    $backURL = $_SERVER['HTTP_REFERER'];
}