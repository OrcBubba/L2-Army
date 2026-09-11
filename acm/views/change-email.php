<?php 
if(!defined('l2jmobius')) {
    die('Direct access not permitted');
} 
?><div class="container-fluid">

	<!-- Page Heading -->
	<h1 class="h3 mb-2 text-gray-800"><?=_('Change your email');?></h1>
	<p class="mb-4"><?=_('Fill in the following form in order to change your email.');?></p>
	
	<?php if(isset($alert)){
		echo '
		<div class="alert alert-'.$alert['type'].'">'.$alert['message'].'</div>'; 
	} ?>

	<div class="card shadow mb-4">
		<?php if(isset($_POST['new_email'])){ ?>
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary"><?=_('Approve this change');?></h6>
		</div>
		<div class="card-body">
			<form method="post" id="change-password">
				<input type="hidden" name="new_email" value="<?=$_POST['new_email'];?>">
				<div class="form-group">
					<label><?=_('Your new email');?></label>
					<input type="email" class="form-control" readonly value="<?=$_POST['new_email'];?>">
				</div>
				<div class="form-group">
					<label><?=_('6-digit confirmation code');?></label>
					<input type="text" class="form-control" name="code" required autocomplete="off" placeholder="<?=sprintf(_('Type the code we sent to %s'), $account->email);?>">
				</div>
				<small class="form-text text-muted mb-2"><?=_('We sent you an email with a unique 6-digit confirmation code to your current email.');?></small>
				<button type="submit" class="btn btn-primary"><?=_('Complete');?></button>
			</form>
		</div>
		
		<?php } else { ?>
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary"><?=_('Set up your new email');?></h6>
		</div>
		<div class="card-body">
			<form method="post" id="change-password">
				<div class="form-group">
					<label><?=_('Your current email');?></label>
					<input type="email" class="form-control" readonly value="<?=$account->email;?>">
				</div>
				<div class="form-group">
					<label><?=_('New email');?></label>
					<input type="email" class="form-control" name="new_email" required autocomplete="off" placeholder="<?=_('Type your new email');?>">
				</div>
				<button type="submit" class="btn btn-primary"><?=_('Save');?></button>
			</form>
		</div>
		<?php } ?>
	</div>
	
</div>