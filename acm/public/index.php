<?php

define('l2jmobius', true);

require_once(dirname(__FILE__) . '/../helpers/functions.php');



$requestUrl = $_ENV['APP_SCHEME'].'://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$requestUrl = explode('?', $requestUrl)[0];
$requestString = trim(substr($requestUrl, strlen($appURL)), '/');
$urlParams = explode('/', $requestString);
$defaultLanguage = $settings->has('default_language') ? $settings->get('default_language') : 'en';
$queryString = !empty($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : '';
if(empty($urlParams[0])){
	header("Location: ".$appURL."/".$defaultLanguage.$queryString);
	exit;
}
if(!isset($locales[$urlParams[0]])){
	header("Location: ".$appURL."/".$defaultLanguage.'/'.implode('/', $urlParams).$queryString);
	exit;
}
$language_id = strtolower($urlParams[0]);
$language = $locales[strtolower(array_shift($urlParams))];
loadTranslations($language['code']);

if (isset($urlParams[0])) {
    $controllerName = strtolower(array_shift($urlParams));
    $controllerName = strstr($controllerName, '?', true) ?: $controllerName;
}
if (!isset($controllerName) || $controllerName == '')
    $controllerName = 'index';
$cParams = array();
while (Count($urlParams) > 0) {
    $actionName = strtolower(array_shift($urlParams));
    $actionName = strstr($actionName, '?', true) ?: $actionName;
    if ($actionName != '')
        array_push($cParams, $actionName);
}

if (isset($cParams[0]) && file_exists($appControllers . $controllerName . '/' . $cParams[0] . '.php')) {
    $file = $cParams[0];
    unset($cParams[0]);
    $cParams = array_values($cParams);
    $selected_controller = $file;
	$controller_path = $appControllers . $controllerName . '/' . $file . '.php';
	$view_path = $appViews . $controllerName . '/' . $file . '.php';
}
elseif (file_exists($appControllers . $controllerName . '/index.php')){
    $controller_path = $appControllers . $controllerName . '/index.php';
	$view_path = $appViews . $controllerName . '/index.php';
}
elseif (file_exists($appControllers . $controllerName . '.php')){
    $controller_path = $appControllers . $controllerName . '.php';
	$view_path = $appViews . $controllerName . '.php';
}
else{
    handle404();
}

if (!in_array($controllerName,  $controllersWithoutLogin)){
	if(empty($account->login)){
		header("Location: ".$appURL."/login");
		exit;
	}
    require_once($appHelpers . 'template.php');	
}
include($controller_path);


if(file_exists($view_path)){
	if (!in_array($controllerName,  $controllersWithoutLogin))
		loadHeader($page);
	include($view_path);
	if (!in_array($controllerName,  $controllersWithoutLogin))
		endBody($page);
}