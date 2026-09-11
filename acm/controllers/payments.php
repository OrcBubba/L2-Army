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
		$columns = array('date_created', 'char_name', 'account_name', 'item_name', 'payment_method', 'status');
		
		$order = ' ORDER BY '.$columns[$_REQUEST['order'][0]['column']].' '.$_REQUEST['order'][0]['dir'];
	}
	else
		$order = ' ORDER BY id DESC';
	if(isset($_REQUEST['search']))
		$_REQUEST['q'] = $_REQUEST['search']['value'];
	
	if(isset($_POST['postData']['additional'])){
		parse_str($_POST['postData']['additional'], $filters);
	}
	
	
	$sql1 = 'FROM acm_donations WHERE id > ?';
	$params1 = array(0);
	if(isset($_REQUEST['q']) && trim($_REQUEST['q']) != ''){
		$sql1 .= ' AND (char_name LIKE ? OR account_name LIKE ? OR item_name LIKE ?)';
		array_push($params1, '%'.$_REQUEST['q'].'%');
		array_push($params1, '%'.$_REQUEST['q'].'%');
		array_push($params1, '%'.$_REQUEST['q'].'%');
	}
	if(isset($filters)){
		foreach($filters as $key=>$val){
			if($val != '' || (is_array($val) && Count($val) > 0)){
				if($key == 'status'){
					$sql1 .= ' AND status = ?';
					array_push($params1, $val);
				}
				elseif($key == 'payment_method'){
					$sql1 .= ' AND payment_method = ?';
					array_push($params1, $val);
				}
				elseif($key == 'date_min'){
					$sql1 .= ' AND date_created >= ?';
					array_push($params1, $val.' 00:00:00');
				}
				elseif($key == 'date_max'){
					$sql1 .= ' AND date_created <= ?';
					array_push($params1, $val.' 23:59:59');
				}
			}
		}
	}
	$total_query = 'SELECT COUNT(*) AS total '.$sql1;
	$total_rows = 'SELECT * '.$sql1;
	$total_rows .= $order.$sLimit;
	$rows = $db_game->fetch($total_rows, $params1);
	$total = $db_game->row($total_query, $params1);
	
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
		$status = '<span class="badge badge-warning">'._('Pending').'</span>';
		if($row->status == 1)
			$status = '<span class="badge badge-success">'._('Completed').'</span>';
		array_push($datarow, $row->date_created);
		array_push($datarow, '<a href="'.$appURL.'/'.$language_id.'/characters/view/'.$row->char_id.'">'.$row->char_name.'</a>');
		array_push($datarow, '<a href="'.$appURL.'/'.$language_id.'/accounts/view/'.$row->account_name.'">'.$row->account_name.'</a>');
		array_push($datarow, $row->item_name);
		array_push($datarow, $row->payment_method);
		array_push($datarow, $status);
		array_push($data['data'], $datarow);
	}
	
	echo json_encode($data);
	exit();
}

$page = array(
    'title'=>'Payments History',
	'styles'=>array('https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css'),
	'scripts'=>array('https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js','https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js'),
	'js'=>"
	<script>
	
	var table = $('#payments').DataTable({
		ajax: {
		   url: '".$appURL."/".$language_id."/payments/table',
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


