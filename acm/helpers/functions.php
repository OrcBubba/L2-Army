<?php
if (!defined('l2jmobius')) {
    die('Direct access not permitted');
}
require_once  dirname(__FILE__) . ('/settings.php');

function handle404(){
	global $appURL, $language_id;
	header("Location: ".$appURL."/".$language_id."/404");
	exit;
}

function checkUpdates(){
	global $db, $db_game;
	$versionFile = dirname(__FILE__) . '/../.version';

	if(!file_exists($versionFile))
		$version = 0;
	else
		$version = intval(file_get_contents($versionFile));
	if($version == 0){
		$sql = "SHOW TABLES LIKE 'account_login_history'";
		$rows = $db->fetch($sql);
		if(Count($rows) == 0){
			$db->query('CREATE TABLE `account_login_history` (
			  `account` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
			  `ip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
			  `login_date` datetime NOT NULL,
			  `is_game` tinyint NOT NULL DEFAULT \'0\',
			  `id` int UNSIGNED NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
			ALTER TABLE `account_login_history`
			  ADD PRIMARY KEY (`id`),
			  ADD KEY `account_login_history_acc` (`account`),
			  ADD KEY `account_loginhistory_ip` (`ip`);
			ALTER TABLE `account_login_history`
				MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;');
		}
		$sql = "SHOW TABLES LIKE 'acm_settings'";
		$rows = $db_game->fetch($sql);
		if(Count($rows) == 0){
			$db_game->query('CREATE TABLE `acm_settings` ( `name` VARCHAR(50) NOT NULL , `value` TEXT NULL DEFAULT NULL , PRIMARY KEY (`name`)) ENGINE = InnoDB;');
		}
		$sql = "SHOW TABLES LIKE 'acm_donation_items'";
		$rows = $db_game->fetch($sql);
		if(Count($rows) == 0){
			$db_game->query('CREATE TABLE `acm_donation_items` (
			  `id` int UNSIGNED NOT NULL,
			  `item_id` int UNSIGNED NOT NULL,
			  `item_quantity` int UNSIGNED NOT NULL DEFAULT \'1\',
			  `allow_multiple` tinyint(1) NOT NULL DEFAULT \'0\',
			  `max_quantity` int UNSIGNED DEFAULT NULL,
			  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			  `description` text COLLATE utf8mb4_unicode_ci,
			  `price` decimal(10,2) NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
			ALTER TABLE `acm_donation_items`
			  ADD PRIMARY KEY (`id`);
			ALTER TABLE `acm_donation_items`
			  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;');
		}
		$sql = "SHOW TABLES LIKE 'acm_donations'";
		$rows = $db_game->fetch($sql);
		if(Count($rows) == 0){
			$db_game->query('CREATE TABLE `acm_donations` (
			  `id` int UNSIGNED NOT NULL,
			  `char_id` int UNSIGNED DEFAULT NULL,
			  `char_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			  `account_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			  `date_created` datetime DEFAULT NULL,
			  `date_paid` datetime DEFAULT NULL,
			  `donation_id` int UNSIGNED DEFAULT NULL,
			  `item_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			  `payment_method` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			  `price` decimal(10,2) DEFAULT NULL,
			  `status` tinyint(1) DEFAULT \'0\',
			  `payment_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			  `item_id_to_give` int UNSIGNED DEFAULT NULL,
			  `quantity_to_give` int UNSIGNED DEFAULT NULL,
			  `tnx_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
			ALTER TABLE `acm_donations`
			  ADD PRIMARY KEY (`id`),
			  ADD UNIQUE KEY `tnx_id` (`tnx_id`);
			ALTER TABLE `acm_donations`
			  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;');
		}
		$new_version = 1;
	}
	if($version < 2){
		$sql = "SHOW TABLES LIKE 'acm_donations_balance'";
		$rows = $db->fetch($sql);
		if(Count($rows) == 0){
			$db->query('CREATE TABLE `acm_donations_balance` (
			  `id` int UNSIGNED NOT NULL,
			  `account_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			  `date_created` datetime DEFAULT NULL,
			  `date_paid` datetime DEFAULT NULL,
			  `payment_method` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			  `amount` decimal(10,2) DEFAULT NULL,
			  `status` tinyint(1) DEFAULT \'0\',
			  `payment_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			  `tnx_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
			ALTER TABLE `acm_donations_balance`
			  ADD PRIMARY KEY (`id`),
			  ADD UNIQUE KEY `tnx_id` (`tnx_id`);
			ALTER TABLE `acm_donations_balance`
			  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;');
		}
		$new_version = 2;
	}
	if($version < 3){
		$sql = "SHOW TABLES LIKE 'acm_task_manager'";
		$rows = $db_game->fetch($sql);
		if(Count($rows) == 0){
			$db_game->query('CREATE TABLE `acm_task_manager` (
			  `id` int UNSIGNED NOT NULL,
			  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
			  `var1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			  `var2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			  `var3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			  `var4` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			  `status` tinyint(1) NOT NULL DEFAULT \'0\',
			  `date_created` datetime DEFAULT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
			ALTER TABLE `acm_task_manager`
			  ADD PRIMARY KEY (`id`),
			  ADD KEY `acm_task_manager_status` (`status`);
			ALTER TABLE `acm_task_manager`
			  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;');
		}
		$new_version = 3;
	}
	
	if(isset($new_version) && $new_version > $version){
		if (file_put_contents($versionFile, $new_version, LOCK_EX) === false) {
			die('Error updating version file.');
		}
	}
}

function getPortStatus($host, $port){
	$connection = @fsockopen($host, $port, $errno, $errstr, 1);
	if(!$connection){
		return 'Offline';
	}
	elseif (is_resource($connection))
	{
		return 'Online';
	}
	return 'Offline';
}

function formatAmount($amount){
	global $settings;
	if($settings->get('currency') == 'USD')
		return '$'.$amount;
	if($settings->get('currency') == 'BLR')
		return 'Rbls '.$amount;
	if($settings->get('currency') == 'RUB')
		return $amount.' ₽';
	return $amount.'€';
}

function __(string $singular, string $plural, int $count)
{
    return sprintf(ngettext($singular, $plural, $count), $count);
}

function loadTranslations(string $locale, string $domain = 'messages')
{
    $codeset = 'utf8';

    $text_domain = $domain;

    if(defined('LC_MESSAGES')){
        // Linux
        setlocale(LC_MESSAGES, $locale . '.' . $codeset);
        bindtextdomain($text_domain, LOCALES_DIR);
    } 
	else {
        // Windows
        putenv("LC_ALL=" . $locale . '.' . $codeset);
        putenv('LANGUAGE=' . $locale . '.' . $codeset);
        setlocale(LC_ALL, $locale . '.' . $codeset);

        if(file_exists(LOCALES_DIR . DIRECTORY_SEPARATOR . $locale . DIRECTORY_SEPARATOR . 'LC_MESSAGES' . DIRECTORY_SEPARATOR . $text_domain . '.mo')){
            $md5 = md5(file_get_contents(LOCALES_DIR . DIRECTORY_SEPARATOR . $locale . DIRECTORY_SEPARATOR . 'LC_MESSAGES' . DIRECTORY_SEPARATOR . $text_domain . '.mo'));

            $tmpfiles = glob(LOCALES_DIR . DIRECTORY_SEPARATOR . $locale . DIRECTORY_SEPARATOR . 'LC_MESSAGES' . DIRECTORY_SEPARATOR . '*.tmp.mo');
            foreach($tmpfiles as $f){
                unlink($f);
            }

            copy(
                LOCALES_DIR . DIRECTORY_SEPARATOR . $locale . DIRECTORY_SEPARATOR . 'LC_MESSAGES' . DIRECTORY_SEPARATOR . $text_domain . '.mo',
                LOCALES_DIR . DIRECTORY_SEPARATOR . $locale . DIRECTORY_SEPARATOR . 'LC_MESSAGES' . DIRECTORY_SEPARATOR . $md5 . '.tmp.mo'
            );

            $text_domain = $md5 . '.tmp';
        }

        bindtextdomain($text_domain, LOCALES_DIR);
    }

    if (function_exists('bind_textdomain_codeset')) {
        bind_textdomain_codeset($text_domain, 'UTF8');
    }
    textdomain($text_domain);
}

function changeLanguage($lang){
	global $appURL, $cParams, $controllerName, $selected_controller;
	$url = $appURL.'/'.$lang;
	if(!empty($selected_controller))
		$url .= '/'.$controllerName.'/'.$selected_controller;
	elseif($controllerName != 'index')
		$url .= '/'.$controllerName;
	if(!empty($cParams))
		$url .= '/'.implode('/', $cParams);
	return $url;
}