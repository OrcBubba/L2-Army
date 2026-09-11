<?php 
if(!defined('l2jmobius')) {
    die('Direct access not permitted');
} 
?><div class="container-fluid">

	<!-- Page Heading -->
	<h1 class="h3 mb-2 text-gray-800"><?=_('Characters');?></h1>
	<p class="mb-4"><?=_('This is the list of your characters. If a character gets stucked upon login, you can unstuck and repair it.');?></p>
	
	<?php if(isset($alert)){
		echo '
		<div class="alert alert-'.$alert['type'].'">'.$alert['message'].'</div>'; 
	} ?>

	<!-- DataTales Example -->
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary"><?=_('Your characters');?></h6>
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
							<th><?=_('Actions');?></th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th><?=_('Name');?></th>
							<th><?=_('Status');?></th>
							<th><?=_('Class');?></th>
							<th><?=_('Level');?></th>
							<th><?=_('Last login');?></th>
							<th><?=_('Actions');?></th>
						</tr>
					</tfoot>
					<tbody>
					<?php
					if(Count($characters) == 0)
						echo '
						<tr>
							<td colspan="6" class="text-center">'._('You haven\'t created any characters.').'</td>
						</tr>';
					foreach($characters as $row){
						$status = '<span class="badge badge-warning">'._('Offline').'</span>';
						if($row->online == 1)
							$status = '<span class="badge badge-success">'._('Online').'</span>';
						if($row->accesslevel < 0)
							$status = '<span class="badge badge-danger">'._('Banned').'</span>';
						$class = '';
						if(isset($classes[$row->classid]))
							$class = $classes[$row->classid];
						$last_login = '';
						if($row->lastAccess > 0)
							$last_login = date('Y-m-d H:i:s', intval($row->lastAccess / 1000));
						echo '
						<tr>
							<td>'.$row->char_name.'</td>
							<td>'.$status.'</td>
							<td>'.$class.'</td>
							<td>'.$row->level.'</td>
							<td>'.$last_login.'</td>
							<td><a href="'.$appURL.'/'.$language_id.'?unstuck='.$row->charId.'">Unstuck</a></td>
						</tr>';
					}
					?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

</div>