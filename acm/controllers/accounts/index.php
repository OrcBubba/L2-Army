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
		$columns = array('created_time', 'login', 'lastactive', 'accessLevel', '', 'lastIP');
		
		$order = ' ORDER BY '.$columns[$_REQUEST['order'][0]['column']].' '.$_REQUEST['order'][0]['dir'];
	}
	else
		$order = ' ORDER BY created_time DESC';
	if(isset($_REQUEST['search']))
		$_REQUEST['q'] = $_REQUEST['search']['value'];
	
	if(isset($_POST['postData']['additional'])){
		parse_str($_POST['postData']['additional'], $filters);
	}
	
	$filters_query = [];
	
	$sql1 = 'FROM accounts';
	$params1 = array();
	if(isset($_REQUEST['q']) && trim($_REQUEST['q']) != ''){
		$filters_query[] = '(login LIKE ? OR lastIP LIKE ?)';
		array_push($params1, '%'.$_REQUEST['q'].'%');
		array_push($params1, '%'.$_REQUEST['q'].'%');
	}
	if(isset($filters)){
		foreach($filters as $key=>$val){
			if($val != '' || (is_array($val) && Count($val) > 0)){
				if($key == 'access_min'){
					$filters_query[] = 'accessLevel >= ?';
					array_push($params1, $val);
				}
				elseif($key == 'access_max'){
					$filters_query[] = 'accessLevel <= ?';
					array_push($params1, $val);
				}
				elseif($key == 'ip'){
					$filters_query[] = 'lastIP = ?';
					array_push($params1, $val);
				}
				elseif($key == 'date_min'){
					$filters_query[] = 'created_time >= ?';
					array_push($params1, $val.' 00:00:00');
				}
				elseif($key == 'date_max'){
					$filters_query[] = 'created_time <= ?';
					array_push($params1, $val.' 23:59:59');
				}
				elseif($key == 'last_active_min'){
					$filters_query[] = 'lastactive >= ?';
					array_push($params1, strtotime($val)*1000);
				}
				elseif($key == 'last_active_max'){
					$filters_query[] = 'lastactive < ?';
					array_push($params1, (strtotime($val) + 86400)*1000);
				}
			}
		}
	}
	if(Count($filters_query) > 0){
		$sql1 .= ' WHERE '.implode(' AND ', $filters_query);
	}
	$total_query = 'SELECT COUNT(*) AS total '.$sql1;
	$total_rows = 'SELECT accounts.* '.$sql1;
	$total_rows .= $order.$sLimit;
	if(Count($params) > 0){
		$rows = $db->fetch($total_rows, $params1);
		$total = $db->row($total_query, $params1);
	}
	else {
		$rows = $db->fetch($total_rows);
		$total = $db->row($total_query);
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
		
		$sql = 'SELECT COUNT(*) as characters FROM characters WHERE account_name = ?';
		$params = array($row->login);
		$total = $db_game->row($sql, $params);
		
		if($row->lastactive > 0)
			$row->lastactive = date('Y-m-d H:i:s', intval($row->lastactive/1000));
		else
			$row->lastactive = '';
		
		array_push($datarow, '<a href="'.$appURL.'/'.$language_id.'/accounts/view/'.$row->login.'">'.$row->created_time.'</a>');
		array_push($datarow, '<a href="'.$appURL.'/'.$language_id.'/accounts/view/'.$row->login.'">'.$row->login.'</a>');
		array_push($datarow, $row->lastactive);
		array_push($datarow, $row->accessLevel);
		array_push($datarow, $total->characters);
		array_push($datarow, $row->lastIP);
		array_push($data['data'], $datarow);
	}
	
	echo json_encode($data);
	exit();
}

$page = array(
    'title'=>_('Game accounts'),
	'styles'=>array('https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css'),
	'scripts'=>array('https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js','https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js'),
	'js'=>"
	<script>
	
	var table = $('#accounts').DataTable({
		ajax: {
		   url: '".$appURL."/".$language_id."/accounts/table',
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
	</script>
	"
);


if(isset($_SESSION['alert'])){
	$alert = $_SESSION['alert'];
	unset($_SESSION['alert']);
}