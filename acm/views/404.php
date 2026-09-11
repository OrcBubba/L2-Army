<?php 
if(!defined('l2jmobius')) {
    die('Direct access not permitted');
} 
?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="<?=$appName;?>">

    <title><?=$page['title'];?> - <?=$appName;?></title>

    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?=$cdnURL;?>/css/style.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-md-4">
				<div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
						<div class="p-5">
							<div class="text-center">
								<h1 class="h4 text-gray-900 mb-4">404</h1>
								<p><?=_('Page was not found...');?></p>
								<a href="<?=$backURL;?>" class="btn btn-primary mt-3"><?=_('Go back');?></a>
							</div>
							
						</div>
                    </div>
                </div>
            </div>

        </div>

    </div>
	
	
    <!-- Bootstrap core JavaScript-->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?=$cdnURL;?>/js/main.js"></script>
	

</body>

</html>