<?php 
if(!defined('l2jmobius')) {
    die('Direct access not permitted');
} 
?><div class="container-fluid">

	<!-- Page Heading -->
	<h1 class="h3 mb-2 text-gray-800"><?=_('Payments History');?></h1>
	<p class="mb-4"><?=_('This is a list with all the payments made by users.');?></p>

	<div class="card shadow mb-4">
		<div class="card-header py-3 d-flex align-items-center justify-content-between">
			<h6 class="m-0 font-weight-bold text-primary"><?=_('List of payments');?></h6>
			<button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#filters-modal"><i class="fa fa-search"></i> <?=_('Filters');?></button>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" width="100%" cellspacing="0" id="payments">
					<thead>
						<tr>
							<th><?=_('Date');?></th>
							<th><?=_('Character');?></th>
							<th><?=_('Account');?></th>
							<th><?=_('Item');?></th>
							<th><?=_('Payment method');?></th>
							<th><?=_('Status');?></th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th><?=_('Date');?></th>
							<th><?=_('Character');?></th>
							<th><?=_('Account');?></th>
							<th><?=_('Item');?></th>
							<th><?=_('Payment method');?></th>
							<th><?=_('Status');?></th>
						</tr>
					</tfoot>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>

</div>

<div class="modal fade" id="filters-modal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="post" id="filters-form">
				<div class="modal-header">
					<h5 class="modal-title"><?=_('Search filters');?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label><?=_('Status');?></label>
						<select class="form-control" name="status">
							<option value=""><?=_('All');?></option>
							<option value="0"><?=_('Pending');?></option>
							<option value="1"><?=_('Completed');?></option>
						</select>
					</div>
					<div class="form-group">
						<label><?=_('Payment method');?></label>
						<select class="form-control" name="payment_method">
							<option value=""><?=_('All');?></option>
							<option value="stripe">Stripe</option>
							<option value="paypal">Paypal (Live)</option>
							<option value="paypal_sandbox">Paypal (Sandbox)</option>
						</select>
					</div>
					<div class="form-group">
						<label><?=_('Date');?></label>
						<div class="row">
							<div class="col-md-6">
								<input type="date" name="date_min" placeholder="<?=_('Min');?>" class="form-control">
							</div>
							<div class="col-md-6">
								<input type="date" name="date_max" placeholder="<?=_('Max');?>" class="form-control">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer d-flex align-items-center justify-content-between">
					<button type="button" class="btn btn-secondary" data-dismiss="modal"><?=_('Cancel');?></button>
					<button type="submit" class="btn btn-primary"><?=_('Apply');?></button>
				</div>
			</form>
		</div>
	</div>
</div>