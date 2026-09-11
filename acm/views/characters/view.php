<?php 
if(!defined('l2jmobius')) {
    die('Direct access not permitted');
} 
?><div class="container-fluid">

	<!-- Page Heading -->
	<h1 class="h3 mb-2 text-gray-800"><?=$row->char_name;?></h1>
	<p class="mb-4"><?=_('Edit the information of the character.');?></p>
	
	<?php if(isset($alert)){
		echo '
		<div class="alert alert-'.$alert['type'].'">'.$alert['message'].'</div>'; 
	} ?>

	<div class="row">
		<div class="col-md-6 col-xl-4">
			<div class="card shadow mb-4">
				<div class="card-header py-3">
					<h6 class="m-0 font-weight-bold text-primary"><?=_('Character information');?></h6>
				</div>
				<div class="card-body">
					<form id="email-form" method="post">
						<div class="form-group">
							<label><?=_('Name');?></label>
							<input type="text" class="form-control" readonly value="<?=$row->char_name;?>">
						</div>
						<div class="form-group">
							<label><a href="<?=$appURL.'/'.$language_id.'/accounts/view/'.$row->account_name;?>" target="_blank"><?=_('Account');?></a></label>
							<input type="text" class="form-control" readonly value="<?=$row->account_name;?>">
						</div>
						<div class="form-group">
							<label><?=_('Date created');?></label>
							<input type="text" class="form-control" readonly value="<?=$row->createDate;?>">
						</div>
						<div class="form-group">
							<label>Access level</label>
							<input type="text" class="form-control" readonly value="<?=$row->accesslevel;?>">
						</div>
						<div class="form-group">
							<label>Class</label>
							<input type="text" class="form-control" readonly value="<?=(isset($classes[$row->classid])) ? $classes[$row->classid] : 'Unknown';?>">
						</div>
						<div class="form-group">
							<label><?=_('Last Online');?></label>
							<input type="text" class="form-control" readonly value="<?=($row->lastAccess > 0) ? date('Y-m-d H:i:s', intval($row->lastAccess / 1000)) : 'Never';?>">
						</div>
						<div class="form-group">
							<label><?=_('Status');?></label>
							<?=($row->online == 1) ? '<span class="badge badge-success">'._('Online').'</span>' : '<span class="badge badge-warning">'._('Offline').'</span>'; ?>
						</div>
						<div class="dropdown d-flex justify-content-end">
							<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"><?=_('Actions');?></button>
							<div class="dropdown-menu">
								<a class="dropdown-item" href="#" data-toggle="modal" data-target="#send-item"><?=_('Send an item');?></a>
								<a class="dropdown-item" href="#" data-toggle="modal" data-target="#change-access"><?=_('Change access level');?></a>
								<?php if($settings->check('enable_task_manager')){ ?>
								<a class="dropdown-item" href="#" data-toggle="modal" data-target="#remove-item"><?=_('Destroy an item');?></a>
								<a class="dropdown-item confirm-action" href="<?=$appURL.'/'.$language_id.'/characters/view/'.$row->charId;?>/remove-buffs"><?=_('Remove all buffs/effects');?></a>
								<a class="dropdown-item confirm-action" href="<?=$appURL.'/'.$language_id.'/characters/view/'.$row->charId;?>/kick"><?=_('Kick from game');?></a>
								<a class="dropdown-item" href="#" data-toggle="modal" data-target="#punish-modal"><?=_('Punish player');?></a>
								<a class="dropdown-item" href="#" data-toggle="modal" data-target="#unpunish-modal"><?=_('Unpunish player');?></a>
								<?php } ?>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-xl-8">
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
									<th><?=_('Item');?></th>
									<th><?=_('Payment method');?></th>
									<th><?=_('Status');?></th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th><?=_('Date');?></th>
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
							foreach($donations as $row){
								
								$status = '<span class="badge badge-warning">'._('Pending').'</span>';
								if($row->status == 1)
									$status = '<span class="badge badge-success">'._('Completed').'</span>';
								echo '
								<tr>
									<td>'.$row->date_created.'</td>
									<td>'.$row->item_name.'</td>
									<td>'.ucfirst($row->payment_method).'</td>
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

<div class="modal fade" id="send-item" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="post" id="send-item-form">
				<div class="modal-header">
					<h5 class="modal-title"><?=_('Send an item to the character');?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label><?=_('Item ID');?></label>
						<input type="number" class="form-control" name="new_item" placeholder="<?=_('Type the item\'s ID');?>" required autocomplete="off">
					</div>
					<div class="form-group">
						<label><?=_('Quantity');?></label>
						<input type="number" class="form-control" name="quantity" placeholder="<?=_('Type the desired quantity');?>" required autocomplete="off" value="1" step="1">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal"><?=_('Close');?></button>
					<button type="submit" class="btn btn-primary"><?=_('Save');?></button>
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
					<h5 class="modal-title"><?=_('Change the character\'s access level');?></h5>
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
					<button type="submit" class="btn btn-primary"><?=_('Save');?></button>
				</div>
			</form>
		</div>
	</div>
</div>
<?php if($settings->check('enable_task_manager')){ ?>
<div class="modal fade" id="remove-item" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?=_('Remove an item from the character\'s inventory');?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<th>Item ID</th>
								<th>Quantity</th>
								<th>Enchant lv.</th>
								<th>Location</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
						<?php
						foreach($items as $item){
							echo '
							<tr>
								<td>'.$item->item_id.'</td>
								<td>'.$item->count.'</td>
								<td>'.$item->enchant_level.'</td>
								<td>'.$item->loc.'</td>
								<td><a class="remove-item text-danger" href="#" data-id="'.$item->object_id.'" data-count="'.$item->count.'">Remove</a></td>
							</tr>';
						}
						?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal"><?=_('Close');?></button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="confirm-item-removal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="post" id="remove-item-form">
				<input type="hidden" name="destroy_item_id">
				<div class="modal-header">
					<h5 class="modal-title"><?=_('Confirm deletion of the item');?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label><?=_('How many would you like to destroy?');?></label>
						<input type="number" class="form-control" name="item_quantity" min="1" required step="1">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal"><?=_('Close');?></button>
					<button type="submit" class="btn btn-primary"><?=_('Save');?></button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="modal fade" id="punish-modal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="post" id="punish-form">
				<div class="modal-header">
					<h5 class="modal-title"><?=_('Punish player');?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label><?=_('Select the punishment type');?></label>
						<select class="form-control" name="punish_type" required>
							<option value=""><?=_('Select');?></option>
							<option value="jail">Jail</option>
							<option value="chat">Chat</option>
						</select>
					</div>
					<div class="form-group">
						<label><?=_('Duration in minutes');?></label>
						<input type="number" min="0" class="form-control" name="duration" required>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal"><?=_('Close');?></button>
					<button type="submit" class="btn btn-primary"><?=_('Save');?></button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="modal fade" id="unpunish-modal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="post" id="unpunish-form">
				<div class="modal-header">
					<h5 class="modal-title"><?=_('Unpunish player');?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label><?=_('Select the punishment type');?></label>
						<select class="form-control" name="unpunish_type" required>
							<option value=""><?=_('Select');?></option>
							<option value="jail">Jail</option>
							<option value="chat">Chat</option>
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal"><?=_('Close');?></button>
					<button type="submit" class="btn btn-primary"><?=_('Save');?></button>
				</div>
			</form>
		</div>
	</div>
</div>
<?php } ?>