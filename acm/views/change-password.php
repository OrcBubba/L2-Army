<?php 
if(!defined('l2jmobius')) {
    die('Direct access not permitted');
} 
?><div class="container-fluid">

	<!-- Page Heading -->
	<h1 class="h3 mb-2 text-gray-800"><?=_('Change password');?></h1>
	<p class="mb-4"><?=_('Fill in the following form in order to change your password.');?></p>
	
	<?php if(isset($alert)){
		echo '
		<div class="alert alert-'.$alert['type'].'">'.$alert['message'].'</div>'; 
	} ?>

	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary"><?=_('Set up a new password');?></h6>
		</div>
		<div class="card-body">
			<form method="post" id="change-password">
				<div class="form-group">
					<label><?=_('Current password');?></label>
					<input type="password" class="form-control" name="current_password" required autocomplete="off" placeholder="<?=_('Type your current password');?>">
				</div>
				<div class="form-group">
					<label><?=_('New password');?></label>
					<input type="password" class="form-control" name="password1" required autocomplete="off" placeholder="<?=_('Type your new password');?>">
				</div>
				<div class="form-group">
					<label><?=_('New password (Repeat)');?></label>
					<input type="password" class="form-control" name="password2" required autocomplete="off" placeholder="<?=_('Retype your new password');?>">
				</div>
				<small class="form-text text-muted mb-2"><?=_('Your new password must be 6-20 characters long and only contain letters from A to Z, numbers from 0 to 9 and the following special characters: . ! @ # $ % ^ & * ( ) - + = _ /');?></small>
				<button type="submit" class="btn btn-primary"><?=_('Save');?></button>
			</form>
		</div>
	</div>
	
</div>