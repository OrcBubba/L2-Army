<?php 
if(!defined('l2jmobius')) {
    die('Direct access not permitted');
} 
?><div class="container-fluid">

	<!-- Page Heading -->
	<h1 class="h3 mb-2 text-gray-800"><?=_('Donations History');?></h1>
	<p class="mb-4"><?=_('This is a list with all your donation history. Thank you very much for your support!');?></p>
	
	<?php if(isset($alert)){
		echo '
		<div class="alert alert-'.$alert['type'].'">'.$alert['message'].'</div>'; 
	} ?>

	<!-- DataTales Example -->
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary"><?=_('Your past donations');?></h6>
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
							<td colspan="6" class="text-center">'._('You haven\'t made any donations.').'</td>
						</tr>';
					foreach($donations as $row){
						
						$status = '<span class="badge badge-warning">'._('Pending').'</span>';
						if($row->status == 1)
							$status = '<span class="badge badge-success">'._('Completed').'</span>';
						echo '
						<tr>
							<td>'.$row->date_created.'</td>
							<td>'.$row->char_name.'</td>
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