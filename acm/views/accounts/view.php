<?php 
if(!defined('l2jmobius')) {
    die('Direct access not permitted');
} 
?><div class="container-fluid">

	<!-- Page Heading -->
	<h1 class="h3 mb-2 text-gray-800"><?=$row->login;?></h1>
	<p class="mb-4"><?=_('Edit the information of the account.');?></p>
	
	<?php if(isset($alert)){
		echo '
		<div class="alert alert-'.$alert['type'].'">'.$alert['message'].'</div>'; 
	} ?>

	<div class="row">
		<div class="col-md-6 col-xl-4">
			<div class="card shadow mb-4">
				<div class="card-header py-3">
					<h6 class="m-0 font-weight-bold text-primary"><?=_('Account information');?></h6>
				</div>
				<div class="card-body">
					<form id="email-form" method="post">
						<div class="form-group">
							<label>Account</label>
							<input type="text" class="form-control" readonly value="<?=$row->login;?>">
						</div>
						<div class="form-group">
							<label>Email</label>
							<input type="text" class="form-control" readonly value="<?=$row->email;?>">
						</div>
						<div class="form-group">
							<label><?=_('Date created');?></label>
							<input type="text" class="form-control" readonly value="<?=$row->created_time;?>">
						</div>
						<div class="form-group">
							<label>Access level</label>
							<input type="text" class="form-control" readonly value="<?=$row->accessLevel;?>">
						</div>
						<div class="form-group">
							<label><?=_('Last IP');?></label>
							<input type="text" class="form-control" readonly value="<?=$row->lastIP;?>">
						</div>
						<div class="form-group">
							<label><?=_('Last Online Date');?></label>
							<input type="text" class="form-control" readonly value="<?=($row->lastactive > 0) ? date('Y-m-d H:i:s', intval($row->lastactive / 1000)) : 'Never';?>">
						</div>
						<?php if($settings->check('use_balance')){ ?>
						<div class="form-group">
							<label><?=_('Account balance');?></label>
							<input type="text" class="form-control" readonly value="<?=formatAmount($row->balance);?>">
						</div>
						<?php } ?>
						<div class="form-group">
							<label><?=_('Accounts with the same IP');?>:</label>
							<div>
							<?php 
							$i = 0;
							foreach($account_names as $r){
								if($i > 0)
									echo ', ';
								echo '<a href="'.$appURL.'/'.$language_id.'/accounts/view/'.$r.'">'.$r.'</a>';
								$i++;
							} ?>
							</div>
						</div>
						
						<div class="dropdown d-flex justify-content-end">
							<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"><?=_('Actions');?></button>
							<div class="dropdown-menu">
								<a class="dropdown-item" href="#" data-toggle="modal" data-target="#change-password"><?=_('Change password');?></a>
								<a class="dropdown-item" href="#" data-toggle="modal" data-target="#change-email"><?=_('Change email');?></a>
								<a class="dropdown-item" href="#" data-toggle="modal" data-target="#change-access"><?=_('Change access level');?></a>
								<?php if($settings->check('use_balance')){ ?>
								<a class="dropdown-item" href="#" data-toggle="modal" data-target="#change-balance"><?=_('Change balance');?></a>
								<?php } ?>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-xl-4">
			<div class="card shadow mb-4">
				<div class="card-header py-3">
					<h6 class="m-0 font-weight-bold text-primary"><?=_('List of characters');?></h6>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-bordered" width="100%" cellspacing="0">
							<thead>
								<tr>
									<th><?=_('Name');?></th>
									<th><?=_('Status');?></th>
									<th><?=_('Class');?></th>
									<th><?=_('Level');?></th>
									<th><?=_('Last login');?></th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th><?=_('Name');?></th>
									<th><?=_('Status');?></th>
									<th><?=_('Class');?></th>
									<th><?=_('Level');?></th>
									<th><?=_('Last login');?></th>
								</tr>
							</tfoot>
							<tbody>
							<?php
							if(Count($characters) == 0)
								echo '
								<tr>
									<td colspan="6" class="text-center">This account does not have any characters.</td>
								</tr>';
							foreach($characters as $r){
								$status = '<span class="badge badge-warning">'._('Offline').'</span>';
								if($r->online == 1)
									$status = '<span class="badge badge-success">'._('Online').'</span>';
								if($r->accesslevel < 0)
									$status = '<span class="badge badge-danger">'._('Banned').'</span>';
								$class = '';
								if(isset($classes[$r->classid]))
									$class = $classes[$r->classid];
								$last_login = '';
								if($r->lastAccess > 0)
									$last_login = date('Y-m-d H:i:s', intval($r->lastAccess / 1000));
								echo '
								<tr>
									<td><a href="'.$appURL.'/'.$language_id.'/characters/view/'.$r->charId.'">'.$r->char_name.'</a></td>
									<td>'.$status.'</td>
									<td>'.$class.'</td>
									<td>'.$r->level.'</td>
									<td>'.$last_login.'</td>
								</tr>';
							}
							?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="card shadow mb-4">
				<div class="card-header py-3">
					<h6 class="m-0 font-weight-bold text-primary"><?=_('Login history');?></h6>
				</div>
				<div class="card-body">
					<div class="table-responsive" style="max-height: 600px">
						<table class="table table-bordered" width="100%" cellspacing="0">
							<thead>
								<tr>
									<th><?=_('Date');?></th>
									<th>IP</th>
									<th><?=_('Login type');?></th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th><?=_('Date');?></th>
									<th>IP</th>
									<th><?=_('Login type');?></th>
								</tr>
							</tfoot>
							<tbody>
							<?php
							if(Count($history) == 0)
								echo '
								<tr>
									<td colspan="6" class="text-center">This account has never logged in.</td>
								</tr>';
							foreach($history as $r){
								echo '
								<tr>
									<td>'.$r->login_date.'</td>
									<td>'.$r->ip.'</td>
									<td>'.(($r->is_game == 0) ? 'Website' : 'Game').'</td>
								</tr>';
							}
							?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-xl-4">
			<div class="card shadow mb-4">
				<div class="card-header py-3">
					<h6 class="m-0 font-weight-bold text-primary"><?=_('Donation history');?></h6>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-bordered" width="100%" cellspacing="0">
							<thead>
								<tr>
									<th><?=_('Date');?></th>
									<th><?=_('Character');?></th>
									<th><?=_('Item');?></th>
									<th><?=_('Payment method');?></th>
									<th><?=_('Status');?></th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th><?=_('Date');?></th>
									<th><?=_('Character');?></th>
									<th><?=_('Item');?></th>
									<th><?=_('Payment method');?></th>
									<th><?=_('Status');?></th>
								</tr>
							</tfoot>
							<tbody>
							<?php
							if(Count($donations) == 0)
								echo '
								<tr>
									<td colspan="6" class="text-center">'._('This account hasn\'t made any donations.').'</td>
								</tr>';
							foreach($donations as $r){
								
								$status = '<span class="badge badge-warning">'._('Pending').'</span>';
								if($r->status == 1)
									$status = '<span class="badge badge-success">'._('Completed').'</span>';
								echo '
								<tr>
									<td>'.$r->date_created.'</td>
									<td><a href="'.$appURL.'/'.$language_id.'/characters/view/'.$r->char_id.'">'.$r->char_name.'</a></td>
									<td>'.$r->item_name.'</td>
									<td>'.ucfirst($r->payment_method).'</td>
									<td>'.$status.'</td>
								</tr>';
							}
							?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		
	</div>
	

</div>

<div class="modal fade" id="change-password" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="post" id="change-password-form">
				<div class="modal-header">
					<h5 class="modal-title"><?=_('Change the account\'s password');?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label><?=_('New password');?></label>
						<input type="text" class="form-control" name="new_password" placeholder="<?=_('Type the new password');?>" required autocomplete="off">
					</div>
					<small class="form-text text-muted mb-2"><?=_('The new password must be 6-20 characters long and only contain letters from A to Z, numbers from 0 to 9 and the following special characters: . ! @ # $ % ^ & * ( ) - + = _ /');?></small>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal"><?=_('Close');?></button>
					<button type="submit" class="btn btn-primary"><?=_('Save changes');?></button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="modal fade" id="change-email" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="post" id="change-email-form">
				<div class="modal-header">
					<h5 class="modal-title"><?=_('Change the account\'s email');?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label><?=_('New email');?></label>
						<input type="email" class="form-control" name="new_email" placeholder="<?=_('Type the new email');?>" required autocomplete="off">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal"><?=_('Close');?></button>
					<button type="submit" class="btn btn-primary"><?=_('Save changes');?></button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="modal fade" id="change-access" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="post" id="change-access-form">
				<div class="modal-header">
					<h5 class="modal-title"><?=_('Change the account\'s access level');?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label><?=_('New access level');?></label>
						<input type="number" class="form-control" name="new_access" placeholder="<?=_('Type the new access level');?>" required autocomplete="off" min="-100" max="100" value="<?=$row->accessLevel;?>">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal"><?=_('Close');?></button>
					<button type="submit" class="btn btn-primary"><?=_('Save changes');?></button>
				</div>
			</form>
		</div>
	</div>
</div>
<?php if($settings->check('use_balance')){ ?>
<div class="modal fade" id="change-balance" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="post" id="change-balance-form">
				<div class="modal-header">
					<h5 class="modal-title"><?=_('Change the account\'s balance');?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label><?=_('New balance');?></label>
						<input type="number" class="form-control" name="new_balance" placeholder="<?=_('Type the new account\'s balance');?>" required autocomplete="off" min="0" value="<?=$row->balance;?>">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal"><?=_('Close');?></button>
					<button type="submit" class="btn btn-primary"><?=_('Save changes');?></button>
				</div>
			</form>
		</div>
	</div>
</div>
<?php } ?>