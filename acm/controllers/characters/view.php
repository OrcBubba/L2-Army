<?php
if (!defined('l2jmobius')) {
    die('Direct access not permitted');
}

if(!$account->isAdmin){
	header("Location: ".$appURL."/".$language_id);
	exit;
}

if(!isset($cParams[0])){
	header("Location: ".$appURL."/".$language_id."/characters");
	exit;
}

$sql = 'SELECT * FROM characters WHERE charId = ?';
$params = array($cParams[0]);
$row = $db_game->row($sql, $params);
if(!isset($row->charId)){
	$_SESSION['alert'] = array(
		'type'=>'danger',
		'message'=>_('Character not found.')
	);
	header("Location: ".$appURL."/".$language_id."/characters");
	exit;
}


if(isset($cParams[1]) && $cParams[1] == 'unstuck'){
	if($row->online == 1) {
		$_SESSION['alert'] = array(
			'type'=>'danger',
			'message'=>_('The player is online. He/she must first log it out of the game.')
		);
		header("Location: ".$appURL."/".$language_id."/characters/view/".$row->charId);
		exit;
	}
	$db_game->update('characters', array('x'=>'83644', 'y'=>'149446', 'z'=>'-3400'), array('charId'=>$row->charId));
	$_SESSION['alert'] = array(
		'type'=>'success',
		'message'=>_('The character has been teleported to Giran Castle Town.')
	);
	header("Location: ".$appURL."/".$language_id."/characters/view/".$row->charId);
	exit;
}

if(isset($_POST['new_access'])){
	$db_game->update('characters', array('accesslevel'=>$_POST['new_access']), array('charId'=>$row->charId));
	$_SESSION['alert'] = array(
		'type'=>'success',
		'message'=>_('The character\'s access level was changed.')
	);
	header("Location: ".$appURL."/".$language_id."/characters/view/".$row->charId);
	exit;
}
elseif(isset($_POST['new_item'])){
	$data = array(
		'receiver'=>$row->charId,
		'subject'=>'Item',
		'message'=>'You received an item from a GM.',
		'items'=>$_POST['new_item'].' '.$_POST['quantity']
	);
	$db_game->insert('custom_mail', $data);
	$_SESSION['alert'] = array(
		'type'=>'success',
		'message'=>sprintf(_('You have send the item with ID %s (x %s) to the character'), '<strong>'.$_POST['new_item'].'</strong>', '<strong>'.$_POST['quantity'].'</strong>')
	);
	header("Location: ".$appURL."/".$language_id."/characters/view/".$row->charId);
	exit;
}


if($settings->check('enable_task_manager')){
	if(isset($cParams[1]) && $cParams[1] == 'remove-buffs'){
		$data = array(
			'name'=>'debuff',
			'var1'=>$row->charId,
			'status'=>0,
			'date_created'=>date('Y-m-d H:i:s')			
		);
		if($db_game->insert('acm_task_manager', $data)){
			$_SESSION['alert'] = array(
				'type'=>'success',
				'message'=>_('The task has been added to the task manager.')
			);
		}
		header("Location: ".$appURL."/".$language_id."/characters/view/".$row->charId);
		exit;
	}
	elseif(isset($cParams[1]) && $cParams[1] == 'kick'){
		$data = array(
			'name'=>'kick',
			'var1'=>$row->charId,
			'status'=>0,
			'date_created'=>date('Y-m-d H:i:s')			
		);
		if($db_game->insert('acm_task_manager', $data)){
			$_SESSION['alert'] = array(
				'type'=>'success',
				'message'=>_('The task has been added to the task manager.')
			);
		}
		header("Location: ".$appURL."/".$language_id."/characters/view/".$row->charId);
		exit;
	}
	if(isset($_POST['destroy_item_id'])){
		$sql = 'SELECT * FROM items WHERE object_id = ? AND owner_id = ?';
		$params = array($_POST['destroy_item_id'], $row->charId);
		$item = $db_game->row($sql, $params);
		if(!isset($item->object_id)){
			$_SESSION['alert'] = array(
				'type'=>'danger',
				'message'=>_('Item not found')
			);
		}
		$location = 0;
		if($item->loc == 'WAREHOUSE')
			$location = 1;
		elseif($item->loc == 'CLANWH')
			$location = 2;
		$data = array(
			'name'=>'destroy_item',
			'var1'=>$row->charId,
			'var2'=>$_POST['destroy_item_id'],
			'var3'=>$_POST['item_quantity'],
			'var4'=>$location,
			'status'=>0,
			'date_created'=>date('Y-m-d H:i:s')			
		);
		if($db_game->insert('acm_task_manager', $data)){
			$_SESSION['alert'] = array(
				'type'=>'success',
				'message'=>_('The task has been added to the task manager.')
			);
		}
		header("Location: ".$appURL."/".$language_id."/characters/view/".$row->charId);
		exit;
	}
	elseif(isset($_POST['punish_type'])){
		$data = array(
			'name'=>'jail',
			'var1'=>$row->charId,
			'var2'=>$_POST['duration'],
			'status'=>0,
			'date_created'=>date('Y-m-d H:i:s')			
		);
		if($_POST['punish_type'] == 'chat')
			$data['name'] = 'chatban';
		if($db_game->insert('acm_task_manager', $data)){
			$_SESSION['alert'] = array(
				'type'=>'success',
				'message'=>_('The task has been added to the task manager.')
			);
		}
		header("Location: ".$appURL."/".$language_id."/characters/view/".$row->charId);
		exit;
	}
	elseif(isset($_POST['unpunish_type'])){
		$data = array(
			'name'=>'unjail',
			'var1'=>$row->charId,
			'status'=>0,
			'date_created'=>date('Y-m-d H:i:s')			
		);
		if($_POST['unpunish_type'] == 'chat')
			$data['name'] = 'unchatban';
		if($db_game->insert('acm_task_manager', $data)){
			$_SESSION['alert'] = array(
				'type'=>'success',
				'message'=>_('The task has been added to the task manager.')
			);
		}
		header("Location: ".$appURL."/".$language_id."/characters/view/".$row->charId);
		exit;
	}
	
}

require_once $appHelpers . 'game-classes.php';

$sql = 'SELECT * FROM acm_donations WHERE char_id = ? AND (status = ? OR date_created > ?) ORDER BY id DESC';
$params = array($row->charId, 1, date('Y-m-d H:i:s', time() - 300));
$donations = $db_game->fetch($sql, $params);

$page = array(
    'title'=>$row->char_name.' - ' . _('Characters')
);

if(isset($_SESSION['alert'])){
	$alert = $_SESSION['alert'];
	unset($_SESSION['alert']);
}

if($settings->check('enable_task_manager')){
	$sql = 'SELECT object_id, item_id, count, enchant_level, loc FROM items WHERE owner_id = ?';
	$params = array($row->charId);
	$items = $db_game->fetch($sql, $params);
	
	$page['js'] = "
	<script>
	$('.remove-item').on('click', function(e){
		e.preventDefault();
		let _id = $(this).data('id');
		let _count = $(this).data('count');
		$('#confirm-item-removal input[name=\"item_quantity\"]').val(_count);
		$('#confirm-item-removal input[name=\"item_quantity\"]').attr('max', _count);
		$('#confirm-item-removal input[name=\"destroy_item_id\"]').val(_id);
		$('#remove-item').modal('hide');
		$('#confirm-item-removal').modal('show');
	})
	$('.confirm-action').on('click', function(e){
		if(!confirm('"._('Are you sure you wish to procceed?')."')){
			e.preventDefault();
			return false;
		}
	})
	$('#confirm-item-removal').on('hidden.bs.modal', function (e){
		$('#remove-item').modal('show');
	})
	</script>
	";
}