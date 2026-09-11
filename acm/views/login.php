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
    <link href="<?=$cdnURL;?>/css/l2army-theme.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary<?=isset($_GET['embed']) ? ' acm-embed' : ''?>">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
				<div class="d-flex justify-content-center mt-4">
					<img src="<?=$cdnURL;?>/img/logo2.png" alt="<?=$appName;?>">
				</div>
				<?php if(isset($alert)){
					echo '
				<div class="alert alert-'.$alert['type'].' mt-3 text-center">'.$alert['message'].'</div>';
				} ?>
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6" id="login-div">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4"><?=sprintf(_('Login to %s'), $appName);?></h1>
                                    </div>
                                    <form class="user" method="post" id="login-form">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" placeholder="<?=_('Account name');?>" name="login_name" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                placeholder="<?=_('Password');?>" name="password" required>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="rememberMe" name="rememberme" value="1">
                                                <label class="custom-control-label" for="rememberMe"><?=_('Remember Me');?></label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            <?=_('Login');?>
                                        </button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small reset-btn" href="#"><?=_('Forgot Password?');?></a>
                                    </div>
                                    <div class="text-center mt-3">
                                        <a href="#" class="btn btn-secondary btn-user btn-block bordered-btn register-btn"><?=_('Create a new account');?></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 d-none" id="register-div">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4"><?=sprintf(_('Register at %s'), $appName);?></h1>
                                    </div>
                                    <form class="user" method="post" id="register-form">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" placeholder="<?=_('Account name');?>" name="register_name" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                placeholder="<?=_('Set a password');?>" name="password1" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                placeholder="<?=_('Retype your password');?>" name="password2" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user"
                                                placeholder="<?=_('Your email');?>" name="email" required>
                                        </div>
										<button type="submit" class="btn btn-primary btn-user btn-block">
                                            <?=_('Register');?>
                                        </button>
										<div class="alert alert-success d-none"><?=_('Your account has been created, but it requires verification. We have sent you an email with a link to verify your account.');?></div>
                                    </form>
                                    <hr>
                                    <div class="text-center mt-3">
                                        <a href="#" class="btn btn-secondary btn-user btn-block bordered-btn login-btn"><?=_('Back to login');?></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 d-none" id="reset-div">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4"><?=_('Reset your password');?></h1>
                                    </div>
                                    <form class="user" method="post" id="reset-form">
                                        <div class="form-group">
											<label><?=_('Type your account\'s name');?></label>
                                            <input type="text" class="form-control form-control-user" placeholder="<?=_('Account name');?>" name="reset_password" required>
                                        </div>
                                        
										<button type="submit" class="btn btn-primary btn-user btn-block">
                                            <?=_('Reset password');?>
                                        </button>
										<div class="alert alert-success d-none"><?=_('We have sent you an email with a link to change your password.');?></div>
                                    </form>
                                    <hr>
                                    <div class="text-center mt-3">
                                        <a href="#" class="btn btn-secondary btn-user btn-block bordered-btn login-btn"><?=_('Back to login');?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
				<?php if($settings->has('main_website')){ ?>
				<div class="d-flex justify-content-center mt-4">
					<a href="<?=$settings->get('main_website');?>" class="text-white"><?=_('Back to main website');?></a>
				</div>
				<?php } ?>
            </div>

        </div>

    </div>
	
	<div class="alert-box d-none">
		<div class="alert alert-danger" role="alert">
			<div class="d-flex align-items-center justify-content-between">
				<h4 class="alert-heading"><?=_('Error');?>...</h4>
				<a href="#" class="close text-danger"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></a>
			</div>
			<p><?=_('Something went wrong. Please try again.');?></p>
		</div>
	</div>

    <!-- Bootstrap core JavaScript-->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?=$cdnURL;?>/js/main.js"></script>
	<script>
	$('#login-form').on('submit', function(e){
		e.preventDefault()
		$.post('<?=$appURL.'/'.$language_id;?>/login', $(this).serialize(), function(data){
			if(data.success){
				window.location.href = '<?=$appURL.'/'.$language_id;?>';
				return;
			}
			$('.alert-box p').html(data.error);
			$('.alert-box').removeClass('d-none');
		}).fail(function(){
			$('.alert-box p').html('<?=_('Something went wrong. Please try again.');?>');
			$('.alert-box').removeClass('d-none');
		})
	})
	$('#register-form').on('submit', function(e){
		e.preventDefault()
		$.post('<?=$appURL.'/'.$language_id;?>/login', $(this).serialize(), function(data){
			if(data.success){
				<?php if($settings->check('require_verification')){ ?>
				$('#register-form button').remove();
				$('#register-form .alert').removeClass('d-none');
				$('#register-form')[0].reset();
				<?php } else { ?>
				window.location.href = '<?=$appURL.'/'.$language_id;?>';
				<?php } ?>
				return;
			}
			$('.alert-box p').html(data.error);
			$('.alert-box').removeClass('d-none');
		}).fail(function(){
			$('.alert-box p').html('<?=_('Something went wrong. Please try again.');?>');
			$('.alert-box').removeClass('d-none');
		})
	})
	$('#reset-form').on('submit', function(e){
		e.preventDefault()
		$.post('<?=$appURL.'/'.$language_id;?>/login', $(this).serialize(), function(data){
			if(data.success){
				$('#reset-form button').remove();
				$('#reset-form .alert').removeClass('d-none');
				$('#reset-form')[0].reset();
				return;
			}
			$('.alert-box p').html(data.error);
			$('.alert-box').removeClass('d-none');
		}).fail(function(){
			$('.alert-box p').html('<?=_('Something went wrong. Please try again.');?>');
			$('.alert-box').removeClass('d-none');
		})
	})
	$('.alert-box .close').on('click', function(e){
		e.preventDefault()
		$('.alert-box').addClass('d-none');
	})
	$('.register-btn').on('click', function(e){
		e.preventDefault();
		$('#login-div').addClass('d-none');
		$('#reset-div').addClass('d-none');
		$('#register-div').removeClass('d-none');
		return;
	})
	$('.login-btn').on('click', function(e){
		e.preventDefault();
		$('#register-div').addClass('d-none');
		$('#reset-div').addClass('d-none');
		$('#login-div').removeClass('d-none');
		return;
	})
	$('.reset-btn').on('click', function(e){
		e.preventDefault();
		$('#register-div').addClass('d-none');
		$('#login-div').addClass('d-none');
		$('#reset-div').removeClass('d-none');
		return;
	})
	</script>

</body>

</html>
