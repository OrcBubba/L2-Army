<?php
if (!defined('l2jmobius')) {
    die('Direct access not permitted');
}

if(!$account->isAdmin){
	header("Location: ".$appURL."/".$language_id);
	exit;
}

if(isset($cParams[0]) && $cParams[0] == 'table'){
	$sLimit = " LIMIT 0,10";
	if ( isset( $_REQUEST['start'] ) && $_REQUEST['length'] != '-1' ) {
		$sLimit = " LIMIT ".intval( $_REQUEST['start'] ).", ".intval( $_REQUEST['length'] );
	}
	if(isset($_REQUEST['order'])){
		$columns = array('date_created', 'name', '', 'status');
		
		$order = ' ORDER BY '.$columns[$_REQUEST['order'][0]['column']].' '.$_REQUEST['order'][0]['dir'];
	}
	else
		$order = ' ORDER BY id DESC';
	if(isset($_REQUEST['search']))
		$_REQUEST['q'] = $_REQUEST['search']['value'];
	
	if(isset($_POST['postData']['additional'])){
		parse_str($_POST['postData']['additional'], $filters);
	}
	
	$filters_query = [];
	
	$sql1 = 'FROM acm_task_manager';
	$params1 = array();
	if(isset($_REQUEST['q']) && trim($_REQUEST['q']) != ''){
		$filters_query[] = '(name LIKE ?)';
		array_push($params1, '%'.$_REQUEST['q'].'%');
	}
	if(isset($filters)){
		foreach($filters as $key=>$val){
			if($val != '' || (is_array($val) && Count($val) > 0)){
				if($key == 'name'){
					$filters_query[] = 'name = ?';
					array_push($params1, $val);
				}
				elseif($key == 'status'){
					$filters_query[] = 'status = ?';
					array_push($params1, $val);
				}
				elseif($key == 'date_min'){
					$filters_query[] = 'date_created >= ?';
					array_push($params1, $val.' 00:00:00');
				}
				elseif($key == 'date_max'){
					$filters_query[] = 'date_created <= ?';
					array_push($params1, $val.' 23:59:59');
				}
			}
		}
	}
	if(Count($filters_query) > 0){
		$sql1 .= ' WHERE '.implode(' AND ', $filters_query);
	}
	$total_query = 'SELECT COUNT(*) AS total '.$sql1;
	$total_rows = 'SELECT acm_task_manager.* '.$sql1;
	$total_rows .= $order.$sLimit;
	if(Count($params) > 0){
		$rows = $db_game->fetch($total_rows, $params1);
		$total = $db_game->row($total_query, $params1);
	}
	else {
		$rows = $db_game->fetch($total_rows);
		$total = $db_game->row($total_query);
	}
	
	if(isset($_POST['draw']))
		$draw = $_POST['draw'];
	else
		$draw = null;
	$data = array(
		'draw'=>$draw,
		'recordsTotal'=>$total->total,
		'recordsFiltered'=>$total->total,
		'iTotalRecords'=>$total->total,
		'iTotalDisplayRecords '=>Count($rows)
	);
	$data['data'] = array();
	
	foreach($rows as $row){
		$datarow = array();
		
		if($row->status == 1)
			$status = '<span class="badge badge-success">'._('Completed').'</span>';
		else
			$status = '<span class="badge badge-warning">'._('Pending').'</span> (<a href="'.$appURL.'/'.$language_id.'/task-manager/remove/'.$row->id.'" class="text-danger confirm-action">Cancel</a>)';
		$info = '';
		$name = '';
		if($row->name == 'kick'){
			$name = 'Player kick';
			$sql = 'SELECT char_name FROM characters WHERE charId = ?';
			$params = array($row->var1);
			$p = $db_game->row($sql, $params);
			if(isset($p->char_name)){
				$info = 'Player: '.$p->char_name;
			}
		}
		elseif($row->name == 'announce'){
			$name = 'Announcement';
			$info = $row->var1;
		}
		elseif($row->name == 'shutdown'){
			$name = 'Shutdown';
			$info = $row->var1.' seconds';
		}
		elseif($row->name == 'restart'){
			$name = 'Restart';
			$info = $row->var1.' seconds';
		}
		elseif($row->name == 'mass_reward'){
			$name = 'Mass reward';
			$item_vars = explode(' ', $row->var3);
			$item_id = $item_vars[0];
			$item_quantity = $item_vars[1];
			$enchant = 0;
			if(isset($item_vars[2]) && $item_vars[2] > 0)
				$enchant = $item_vars[2];
			$info = 'Levels: '.$row->var1.'-'.$row->var2.', Item ID: '.$item_id.', Quantity: '.$item_quantity;
			if($enchant > 0)
				$info .= ', Enchant: '.$enchant;
		}
		elseif($row->name == 'debuff'){
			$name = 'Debuff';
			$sql = 'SELECT char_name FROM characters WHERE charId = ?';
			$params = array($row->var1);
			$p = $db_game->row($sql, $params);
			if(isset($p->char_name)){
				$info = 'Player: '.$p->char_name;
			}
		}
		elseif($row->name == 'chatban'){
			$name = 'Chat ban';
			$sql = 'SELECT char_name FROM characters WHERE charId = ?';
			$params = array($row->var1);
			$p = $db_game->row($sql, $params);
			if(isset($p->char_name)){
				$info = 'Player: '.$p->char_name.', ';
			}
			$info .= 'Duration: '.$row->var2.' minutes';
		}
		elseif($row->name == 'jail'){
			$name = 'Jail';
			$sql = 'SELECT char_name FROM characters WHERE charId = ?';
			$params = array($row->var1);
			$p = $db_game->row($sql, $params);
			if(isset($p->char_name)){
				$info = 'Player: '.$p->char_name.', ';
			}
			$info .= 'Duration: '.$row->var2.' minutes';
		}
		elseif($row->name == 'unchatban'){
			$name = 'Unban chat';
			$sql = 'SELECT char_name FROM characters WHERE charId = ?';
			$params = array($row->var1);
			$p = $db_game->row($sql, $params);
			if(isset($p->char_name)){
				$info = 'Player: '.$p->char_name;
			}
		}
		elseif($row->name == 'unjail'){
			$name = 'Unjail';
			$sql = 'SELECT char_name FROM characters WHERE charId = ?';
			$params = array($row->var1);
			$p = $db_game->row($sql, $params);
			if(isset($p->char_name)){
				$info = 'Player: '.$p->char_name;
			}
		}
		elseif($row->name == 'destroy_item'){
			$name = 'Destroy item';
			$sql = 'SELECT char_name FROM characters WHERE charId = ?';
			$params = array($row->var1);
			$p = $db_game->row($sql, $params);
			if(isset($p->char_name)){
				$info = 'Player: '.$p->char_name;
			}
		}
		
		array_push($datarow, $row->date_created);
		array_push($datarow, $name);
		array_push($datarow, $info);
		array_push($datarow, $status);
		array_push($data['data'], $datarow);
	}
	
	echo json_encode($data);
	exit();
}
if(isset($cParams[1]) && $cParams[0] == 'remove'){
	$sql = 'SELECT id, status FROM acm_task_manager WHERE id = ?';
	$params = array($cParams[1]);
	$row = $db_game->row($sql, $params);
	if(!isset($row->id)){
		$_SESSION['alert'] = array(
			'type'=>'danger',
			'message'=>_('The task was not found.')
		);
		header("Location: ".$appURL."/".$language_id."/task-manager");
		exit;
	}
	if($row->status == 1){
		$_SESSION['alert'] = array(
			'type'=>'danger',
			'message'=>_('The task cannot be cancelled, because has already been completed.')
		);
		header("Location: ".$appURL."/".$language_id."/task-manager");
		exit;
	}
	$db_game->delete('acm_task_manager', array('id'=>$row->id));
	$_SESSION['alert'] = array(
		'type'=>'success',
		'message'=>_('The task has been cancelled and deleted.')
	);
	header("Location: ".$appURL."/".$language_id."/task-manager");
	exit;
}
if(isset($_POST['shutdown'])){
	$data = array(
		'name'=>'shutdown',
		'var1'=>$_POST['shutdown'],
		'status'=>0,
		'date_created'=>date('Y-m-d H:i:s')			
	);
	if($db_game->insert('acm_task_manager', $data)){
		$_SESSION['alert'] = array(
			'type'=>'success',
			'message'=>_('The task has been added to the task manager.')
		);
	}
	header("Location: ".$appURL."/".$language_id."/task-manager");
	exit;
}
elseif(isset($_POST['restart'])){
	$data = array(
		'name'=>'restart',
		'var1'=>$_POST['restart'],
		'status'=>0,
		'date_created'=>date('Y-m-d H:i:s')			
	);
	if($db_game->insert('acm_task_manager', $data)){
		$_SESSION['alert'] = array(
			'type'=>'success',
			'message'=>_('The task has been added to the task manager.')
		);
	}
	header("Location: ".$appURL."/".$language_id."/task-manager");
	exit;
}
elseif(isset($_POST['announce'])){
	$data = array(
		'name'=>'announce',
		'var1'=>$_POST['announce'],
		'status'=>0,
		'date_created'=>date('Y-m-d H:i:s')			
	);
	if($db_game->insert('acm_task_manager', $data)){
		$_SESSION['alert'] = array(
			'type'=>'success',
			'message'=>_('The task has been added to the task manager.')
		);
	}
	header("Location: ".$appURL."/".$language_id."/task-manager");
	exit;
}
elseif(isset($_POST['reward_id'])){
	$item_string = $_POST['reward_id'].' '.$_POST['reward_quantity'];
	if(!empty($_POST['reward_enchant']) && $_POST['reward_enchant'] > 0)
		$item_string .= ' '.$_POST['reward_enchant'];
	$data = array(
		'name'=>'mass_reward',
		'var1'=>$_POST['min_level'],
		'var2'=>$_POST['max_level'],
		'var3'=>$item_string,
		'status'=>0,
		'date_created'=>date('Y-m-d H:i:s')			
	);
	if($db_game->insert('acm_task_manager', $data)){
		$_SESSION['alert'] = array(
			'type'=>'success',
			'message'=>_('The task has been added to the task manager.')
		);
	}
	header("Location: ".$appURL."/".$language_id."/task-manager");
	exit;
}

$page = array(
    'title'=>_('Task Manager'),
	'styles'=>array('https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css'),
	'scripts'=>array('https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js','https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js'),
	'js'=>"
	<script>
	
	var table = $('#tasks-history').DataTable({
		ajax: {
		   url: '".$appURL."/".$language_id."/task-manager/table',
		   type: 'POST',
		   data: function ( d ) {
				d.postData = getPostData();
		   }
			 
		},
		iDisplayLength: 50,
		order: [[ 0, 'desc' ]],
		aaSorting: [], 
		bSortClasses: false,
		processing: true,
		columnDefs: [ {
			  targets: 'no-sort',
			  orderable: false,
		} ],
		serverSide: true		
	});
	$('#filters-form').on('submit', function(e){
		e.preventDefault();
		table.ajax.reload(null, false);
		$('#filters-modal').modal('hide');
		return false;
	});
		
	function getPostData(){
		var data = {};
		data['additional'] = $('#filters-form').serialize();
		return data;
	}
	
	$('body').on('click', '.confirm-action', function(e){
		if(!confirm('"._('Are you sure you wish to procceed?')."')){
			e.preventDefault();
			return false;
		}
	})
	</script>
	"
);


if(isset($_SESSION['alert'])){
	$alert = $_SESSION['alert'];
	unset($_SESSION['alert']);
}
