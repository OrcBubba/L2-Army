<?php
if (!defined('l2jmobius')) {
    die('Direct access not permitted');
}

if(isset($cParams[0]) && $cParams[0] == 'table'){
	$sLimit = " LIMIT 0,10";
	if ( isset( $_REQUEST['start'] ) && $_REQUEST['length'] != '-1' ) {
		$sLimit = " LIMIT ".intval( $_REQUEST['start'] ).", ".intval( $_REQUEST['length'] );
	}
	if(isset($_REQUEST['order'])){
		$columns = array('id', 'ip', 'is_game');
		
		$order = ' ORDER BY '.$columns[$_REQUEST['order'][0]['column']].' '.$_REQUEST['order'][0]['dir'];
	}
	else
		$order = ' ORDER BY id DESC';
	
	if(isset($_REQUEST['search']))
		$_REQUEST['q'] = $_REQUEST['search']['value'];
	
	
	$sql1 = 'FROM account_login_history WHERE account = ?';
	$params1 = array($account->login);
	if(isset($_REQUEST['q']) && trim($_REQUEST['q']) != ''){
		$sql1 .= ' AND ip LIKE ?';
		array_push($params1, '%'.$_REQUEST['q'].'%');
	}
	
	$total_query = 'SELECT COUNT(*) AS total '.$sql1;
	$total_rows = 'SELECT * '.$sql1;
	$total_rows .= $order.$sLimit;
	$rows = $db->fetch($total_rows, $params1);
	$total = $db->row($total_query, $params1);
	
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
		$type = _('Website');
		if($row->is_game == 1)
			$type = _('Game');
		array_push($datarow, $row->login_date);
		array_push($datarow, $row->ip);
		array_push($datarow, $type);
		array_push($data['data'], $datarow);
	}
	
	echo json_encode($data);
	exit();
}

$page = array(
    'title'=>_('Login History'),
	'styles'=>array('https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css'),
	'scripts'=>array('https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js','https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js'),
	'js'=>"
	<script>
	
	var table = $('#history').DataTable({
		ajax: {
		   url: '".$appURL."/".$language_id."/login-history/table',
		   type: 'POST'			 
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
	</script>
	"
);


