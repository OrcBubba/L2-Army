<?php
if (!defined('l2jmobius')) {
    die('Direct access not permitted');
}

function loadHeader($page)
{
    global $cdnURL, $appName, $appURL, $db, $db_game, $appViews, $controllerName, $account, $settings, $language, $locales, $language_id;
    
    echo '<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>';
    if (isset($page['title'])) {
        echo $page['title'] . ' - ';
    }
    echo $appName . '</title>
	<link rel="stylesheet" href="'.$cdnURL.'/css/style.css">
	<link rel="stylesheet" href="'.$cdnURL.'/css/font-awesome.css">
	<link rel="stylesheet" href="'.$cdnURL.'/css/l2army-theme.css">
	';
    if (isset($page['styles'])) {
        foreach ($page['styles'] as $style) {
            echo '<link href="' . $style . '" rel="stylesheet" type="text/css">';
        }
    }
    if (isset($page['css'])) {
        echo '<style>' . $page['css'] . '</style>';
    }
	echo '
</head>
<body id="page-top">
	<div id="wrapper">';
    include($appViews . 'common/header.php');
}
function endBody($page)
{
    global $cdnURL, $appName, $appURL, $db, $appViews, $controllerName;
	echo '</div>';
	include($appViews . 'common/footer.php');
	echo '</div></div>
	<a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>';
	echo '
	<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
	<script src="'.$cdnURL.'/js/main.js"></script>
    ';
    if (isset($page['scripts'])) {
        foreach ($page['scripts'] as $script) {
            echo '<script src="' . $script . '"></script>';
        }
    }
	if(isset($page['js']))
		echo $page['js'];
	echo '
	</body>
</html>';
}