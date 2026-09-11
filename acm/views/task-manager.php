<?php 
if(!defined('l2jmobius')) {
    die('Direct access not permitted');
} 
?><div class="container-fluid">

	<!-- Page Heading -->
	<h1 class="h3 mb-2 text-gray-800"><?=_('Task Manager');?></h1>
	<p class="mb-4"><?=_('Execute tasks from the ACM to the gameserver.');?></p>
	
	<?php if(isset($alert)){
		echo '
		<div class="alert alert-'.$alert['type'].'">'.$alert['message'].'</div>'; 
	} ?>

	<div class="row">
		<div class="col-xl-4">
			<div class="card shadow mb-4">
				<div class="card-header py-3">
					<h6 class="m-0 font-weight-bold text-primary"><?=_('Run a global task');?></h6>
				</div>
				<div class="card-body">
					<a href="#" data-toggle="modal" data-target="#shutdown" class="btn btn-primary w-100 mb-2"><?=_('Shutdown server');?></a>
					<a href="#" data-toggle="modal" data-target="#restart" class="btn btn-primary w-100 mb-2"><?=_('Restart server');?></a>
					<a href="#" data-toggle="modal" data-target="#reward" class="btn btn-primary w-100 mb-2"><?=_('Reward all online players');?></a>
					<a href="#" data-toggle="modal" data-target="#announce" class="btn btn-primary w-100 mb-2"><?=_('Make an announcement');?></a>
				</div>
			</div>
		</div>
		<div class="col-xl-8">
			<div class="card shadow mb-4">
				<div class="card-header py-3 d-flex align-items-center justify-content-between">
					<h6 class="m-0 font-weight-bold text-primary"><?=_('Tasks history');?></h6>
					<button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#filters-modal"><i class="fa fa-search"></i> <?=_('Filters');?></button>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table" id="tasks-history">
							<thead>
								<tr>
									<th>Date</th>
									<th>Task</th>
									<th>Info</th>
									<th>Status</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
		
	</div>

</div>

<div class="modal fade" id="shutdown" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="post" id="shutdown-form">
				<div class="modal-header">
					<h5 class="modal-title"><?=_('Shutdown the gameserver');?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label><?=_('In how many seconds?');?></label>
						<input type="number" class="form-control" name="shutdown" min="0" step="1" placeholder="<?=_('Type the seconds to shutdown the server.');?>" required>
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

<div class="modal fade" id="restart" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="post" id="shutdown-form">
				<div class="modal-header">
					<h5 class="modal-title"><?=_('Restart the gameserver');?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label><?=_('In how many seconds?');?></label>
						<input type="number" class="form-control" name="restart" min="0" step="1" placeholder="<?=_('Type the seconds to restart the server.');?>" required>
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

<div class="modal fade" id="reward" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="post" id="shutdown-form">
				<div class="modal-header">
					<h5 class="modal-title"><?=_('Reward all online players');?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label><?=_('Minimum player level');?></label>
								<input type="number" class="form-control" name="min_level" value="1" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label><?=_('Maximum player level');?></label>
								<input type="number" class="form-control" name="max_level" value="120" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label><?=_('Item ID');?></label>
								<input type="number" class="form-control" name="reward_id" required step="1">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label><?=_('Item quantity');?></label>
								<input type="number" class="form-control" name="reward_quantity" required min="1" value="1" step="1">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label><?=_('Enchant level');?></label>
								<input type="number" class="form-control" name="reward_enchant" min="0" step="1">
							</div>
						</div>
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

<div class="modal fade" id="announce" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="post" id="shutdown-form">
				<div class="modal-header">
					<h5 class="modal-title"><?=_('Make an in-game announcement');?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label><?=_('Type the announcement');?></label>
						<input type="text" class="form-control" name="announce" placeholder="<?=_('Type the announcement.');?>" required>
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
						<label><?=_('Date created');?></label>
						<div class="row">
							<div class="col-md-6">
								<input type="date" name="date_min" placeholder="<?=_('Min');?>" class="form-control">
							</div>
							<div class="col-md-6">
								<input type="date" name="date_max" placeholder="<?=_('Max');?>" class="form-control">
							</div>
						</div>
					</div>
					<div class="form-group">
						<label><?=_('Status');?></label>
						<select class="form-control" name="status">
							<option value=""><?=_('All');?></option>
							<option value="0"><?=_('Pending');?></option>
							<option value="1"><?=_('Completed');?></option>
						</select>
					</div>
					<div class="form-group">
						<label><?=_('Task type');?></label>
						<select class="form-control" name="name">
							<option value=""><?=_('All');?></option>
							<option value="announce">Announce</option>
							<option value="shutdown">Shutdown gameserver</option>
							<option value="restart">Restart gameserver</option>
							<option value="destroy_item">Destroy player item</option>
							<option value="debuff">Remove buffs</option>
							<option value="mass_reward">Mass reward</option>
							<option value="chatban">Chat ban player</option>
							<option value="unchatban">Chat unban player</option>
							<option value="jail">Jail player</option>
							<option value="unjail">Unjail player</option>
							<option value="kick">Kicl player</option>
						</select>
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