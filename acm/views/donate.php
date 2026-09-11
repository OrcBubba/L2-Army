<?php 
if(!defined('l2jmobius')) {
    die('Direct access not permitted');
} 
?><div class="container-fluid">

	<!-- Page Heading -->
	<div class="row">
		<div class="col-md-9">
			<h1 class="h3 mb-2 text-gray-800"><?=_('Donate');?></h1>
			<p class="mb-4"><?=sprintf(_('Donate to %s and help our server grow.'), $appName);?></p>
		</div>
		<?php if($settings->check('use_balance')){ ?>
		<div class="col-md-3 text-center">
			<div class="card">
				<h4><?=_('Available balance').': <span class="text-success">'.formatAmount($account->balance).'</span>';?></h4>
				<a href="#" data-toggle="modal" data-target="#add-balance" class="btn btn-success btn-sm buy-item"><i class="fa fa-heart"></i> <?=_('Add balance');?></a>
			</div>
		</div>
		<?php } ?>
	</div>
	
	<?php if(isset($alert)){
		echo '
		<div class="alert alert-'.$alert['type'].'">'.$alert['message'].'</div>'; 
	} ?>

	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary"><?=_('Available items and services for donation');?></h6>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th><?=_('Item / Service');?></th>
							<th class="text-center"><?=_('Quantity');?></th>
							<th class="text-center"><?=_('Price');?></th>
							<th class="text-center"><?=_('Actions');?></th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th><?=_('Item / Service');?></th>
							<th class="text-center"><?=_('Quantity');?></th>
							<th class="text-center"><?=_('Price');?></th>
							<th class="text-center"><?=_('Actions');?></th>
						</tr>
					</tfoot>
					<tbody>
					<?php
					if(Count($items) == 0)
						echo '
						<tr>
							<td colspan="6" class="text-center">'._('There are no items available for donation right now.').'</td>
						</tr>';
					elseif(Count($characters) == 0)
						echo '
						<tr>
							<td colspan="6" class="text-center">'._('You haven\'t created any characters.').'</td>
						</tr>';
					else {
						if($settings->check('use_balance'))
							$donate_btn = _('Get item');
						else
							$donate_btn = '<i class="fa fa-heart"></i> '._('Donate');
						foreach($items as $row){
							echo '
							<tr>
								<td>'.$row->name.'</td>
								<td class="text-center">x'.$row->item_quantity.'</td>
								<td class="text-center">'.formatAmount($row->price).'</td>
								<td class="text-center"><a href="'.$appURL.'/'.$language_id.'/donate/'.$row->id.'" class="btn btn-success btn-sm buy-item">'.$donate_btn.'</a></td>
							</tr>';
						}
					}
					?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<?php if($settings->has('donations_tos')){ ?>
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary"><?=_('Terms of service');?></h6>
		</div>
		<div class="card-body"><?=Michelf\Markdown::defaultTransform($settings->get('donations_tos'));?></div>
	</div>
	<?php } ?>
</div>
<?php if(isset($donate->id)){ ?>
<div class="modal fade" id="donate" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="post" id="donate-form">
				<input type="hidden" name="donation_id" value="<?=$donate->id;?>">
				<div class="modal-header">
					<h5 class="modal-title"><?=sprintf(_('Donate to %s'), $appName);?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label><?=_('Select your character');?></label>
						<select class="form-control" name="character" required>
							<option value=""><?=_('Choose your character');?></option>
							<?php 
							foreach($characters as $row) 
								echo '<option value="'.$row->charId.'">'.$row->char_name.'</option>';
							?>
						</select>
					</div>
					<div class="form-group">
						<label><?=_('Item');?></label>
						<input type="text" class="form-control" readonly value="<?=$donate->name.' (x'.$donate->item_quantity.')'; ?>">
					</div>
					<?php if($donate->description != ''){ ?>
					<div class="form-group">
						<label><?=_('Description');?></label>
						<textarea class="form-control" readonly><?=$donate->description;?></textarea>
					</div>
					<?php } if($donate->allow_multiple == 1) {?>
					<div class="form-group">
						<label><?=_('Quantity');?></label>
						<div class="input-group">
							<div class="input-group-prepend">
								<button class="btn btn-secondary minus-quantity" type="button" onclick="changeQuantity('decrease')" disabled>-</button>
							</div>
							<input type="number" class="form-control" name="quantity" value="1" min="1"<?php if($donate->max_quantity > 0) echo ' max="'.$donate->max_quantity.'"'; ?> required step="1" onchange="updatePrice()">
							<div class="input-group-append">
								<button class="btn btn-secondary plus-quantity" type="button" onclick="changeQuantity('increase')">+</button>
							</div>
						</div>
						
					</div>
					<?php } ?>
					<div class="form-group">
						<label><?=_('Price');?></label>
						<input type="text" readonly class="form-control" value="<?=formatAmount($donate->price);?>" name="price">
					</div>
					<?php if(!$settings->check('use_balance')){ ?>
					<div class="form-group">
						<label><?=_('Payment method');?></label>
						<select class="form-control" name="payment_method" required>
							<option value=""><?=_('Choose a method to pay');?></option>
							<?php 
							foreach($payment_methods as $key=>$val)
								echo '<option value="'.$key.'">'.$val.'</option>';
							?>
						</select>
					</div>
					<div class="form-group form-check">
						<input type="checkbox" class="form-check-input" id="payment-terms" value="1" required>
						<label class="form-check-label" for="payment-terms"><?=_('I have read and I agree with the Terms of Service that are shown on this page.');?></label>
					</div>
					<small class="form-text text-muted mb-2"><?=_('You will be redirected to the payment gateway\'s secure invironment. We do not store any of your payments information.');?></small>
					<?php } ?>
					
				</div>
				<div class="modal-footer d-flex align-items-center justify-content-between">
					<button type="button" class="btn btn-secondary" data-dismiss="modal"><?=_('Cancel');?></button>
					<button type="submit" class="btn btn-primary"><?=_('Donate');?></button>
				</div>
			</form>
		</div>
	</div>
</div>
<?php } elseif($settings->check('use_balance')){ ?>
<div class="modal fade" id="add-balance" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="post" id="balance-form" action="<?=$appURL.'/'.$language_id.'/donate';?>">
				<div class="modal-header">
					<h5 class="modal-title"><?=sprintf(_('Donate to %s'), $appName);?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label><?=_('Type your desired amount');?></label>
						<div class="input-group">
							<input type="number" class="form-control" min="0" required name="add_balance">
							<div class="input-group-append">
								<span class="input-group-text"><?=$settings->get('currency');?></span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label><?=_('Payment method');?></label>
						<select class="form-control" name="payment_method" required>
							<option value=""><?=_('Choose a method to pay');?></option>
							<?php 
							foreach($payment_methods as $key=>$val)
								echo '<option value="'.$key.'">'.$val.'</option>';
							?>
						</select>
					</div>
					<div class="form-group form-check">
						<input type="checkbox" class="form-check-input" id="payment-terms" value="1" required>
						<label class="form-check-label" for="payment-terms"><?=_('I have read and I agree with the Terms of Service that are shown on this page.');?></label>
					</div>
					<small class="form-text text-muted mb-2"><?=_('You will be redirected to the payment gateway\'s secure invironment. We do not store any of your payments information.');?></small>
					
				</div>
				<div class="modal-footer d-flex align-items-center justify-content-between">
					<button type="button" class="btn btn-secondary" data-dismiss="modal"><?=_('Cancel');?></button>
					<button type="submit" class="btn btn-primary"><?=_('Donate');?></button>
				</div>
			</form>
		</div>
	</div>
</div>
<?php } ?>