<?php 
if(!defined('l2jmobius')) {
    die('Direct access not permitted');
} 
?><div class="container-fluid">

	<!-- Page Heading -->
	<h1 class="h3 mb-2 text-gray-800"><?=_('Login history');?></h1>
	<p class="mb-4"><?=_('This is all the times someone has logged in using your account.');?></p>

	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary"><?=_('Login history');?></h6>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" width="100%" cellspacing="0" id="history">
					<thead>
						<tr>
							<th><?=_('Date');?></th>
							<th><?=_('IP');?></th>
							<th><?=_('Login type');?></th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th><?=_('Date');?></th>
							<th><?=_('IP');?></th>
							<th><?=_('Login type');?></th>
						</tr>
					</tfoot>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>

</div>