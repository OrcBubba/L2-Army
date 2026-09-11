<?php
if (!defined('l2jmobius')) {
    die('Direct access not permitted');
}

if(!$account->isAdmin){
	header("Location: ".$appURL."/".$language_id);
	exit;
}

if($demoMode){
	$settings->set('email', '*******@domain.com');
	$settings->set('mailjet_username', '************');
	$settings->set('mailjet_password', '************');
	$settings->set('smtp_host', '************');
	$settings->set('smtp_username', '************');
	$settings->set('smtp_password', '************');
	$settings->set('stripe_key', '************');
	$settings->set('stripe_webhook', '************');
	$settings->set('paypal_email', '************');
}

if(isset($_POST['disable_registration'])){
	if($demoMode){
		$_SESSION['alert'] = array(
			'type'=>'danger',
			'message'=>_('This is a demo mode and you cannot edit these settings.')
		);
		header("Location: ".$appURL."/".$language_id."/settings");
		exit;
	}
	foreach($_POST as $key=>$val){
		if($val == '' || $val == 0){
			$settings->remove($key);
		}
		else {
			$settings->set($key, $val);
		}
	}
	$settings->store();
	$_SESSION['alert'] = array(
		'type'=>'success',
		'message'=>_('Your general settings have been saved.')
	);
	header("Location: ".$appURL."/".$language_id."/settings");
	exit;
}
elseif(isset($_POST['use_mailjet'])){
	if($demoMode){
		$_SESSION['alert'] = array(
			'type'=>'danger',
			'message'=>_('This is a demo mode and you cannot edit these settings.')
		);
		header("Location: ".$appURL."/".$language_id."/settings");
		exit;
	}
	foreach($_POST as $key=>$val){
		if($val == '' || $val == 0){
			$settings->remove($key);
		}
		else {
			$settings->set($key, $val);
		}
	}
	$settings->store();
	$_SESSION['alert'] = array(
		'type'=>'success',
		'message'=>_('Your email settings have been saved.')
	);
	header("Location: ".$appURL."/".$language_id."/settings");
	exit;
}
elseif(isset($_POST['enable_donations'])){
	if($demoMode){
		$_SESSION['alert'] = array(
			'type'=>'danger',
			'message'=>_('This is a demo mode and you cannot edit these settings.')
		);
		header("Location: ".$appURL."/".$language_id."/settings");
		exit;
	}
	foreach($_POST as $key=>$val){
		if($val == '' || $val == 0){
			$settings->remove($key);
		}
		else {
			$settings->set($key, $val);
		}
	}
	$settings->store();
	$_SESSION['alert'] = array(
		'type'=>'success',
		'message'=>_('Your donation settings have been saved.')
	);
	header("Location: ".$appURL."/".$language_id."/settings");
	exit;
}
elseif(isset($_POST['new_donation_item'])){
	if($demoMode){
		$_SESSION['alert'] = array(
			'type'=>'danger',
			'message'=>_('This is a demo mode and you cannot edit these settings.')
		);
		header("Location: ".$appURL."/".$language_id."/settings");
		exit;
	}
	$data = array();
	foreach($_POST as $key=>$val){
		if($key == 'new_donation_item')
			continue;
		if($val == ''){
			$val = null;
		}
		$data[$key] = $val;
	}
	if(empty($data['allow_multiple']))
		$data['allow_multiple'] = 0;
	if(!empty($data['max_quantity']))
		$data['max_quantity'] = intval($data['max_quantity']);
	$data['price'] = round(floatval($data['price']), 2);
	if($db_game->insert('acm_donation_items', $data)){
		$_SESSION['alert'] = array(
			'type'=>'success',
			'message'=>_('Your item has been added to the donation list.')
		);
	}
	header("Location: ".$appURL."/".$language_id."/settings");
	exit;
}
elseif(isset($_POST['show_donation_item'])){
	header('Content-Type: application/json; charset=utf-8');
	$response = array('success'=>false);
	$sql = 'SELECT * FROM acm_donation_items WHERE id = ?';
	$params = array($_POST['show_donation_item']);
	$row = $db_game->row($sql, $params);
	if(isset($row->id)){
		$response = array('success'=>true, 'item'=>$row);
	}
	echo json_encode($response);
	exit;
}
elseif(isset($_POST['edit_donation_item'])){
	if($demoMode){
		$_SESSION['alert'] = array(
			'type'=>'danger',
			'message'=>_('This is a demo mode and you cannot edit these settings.')
		);
		header("Location: ".$appURL."/".$language_id."/settings");
		exit;
	}
	$sql = 'SELECT * FROM acm_donation_items WHERE id = ?';
	$params = array($_POST['edit_donation_item']);
	$row = $db_game->row($sql, $params);
	if(!isset($row->id)){
		$_SESSION['alert'] = array(
			'type'=>'danger',
			'message'=>_('Something went wrong.')
		);
		header("Location: ".$appURL."/".$language_id."/settings");
		exit;
	}
	$data = array();
	foreach($_POST as $key=>$val){
		if($key == 'edit_donation_item')
			continue;
		if($val == ''){
			$val = null;
		}
		$data[$key] = $val;
	}
	if(empty($data['allow_multiple']))
		$data['allow_multiple'] = 0;
	if(!empty($data['max_quantity']))
		$data['max_quantity'] = intval($data['max_quantity']);
	$data['price'] = round(floatval($data['price']), 2);
	
	
	$db_game->update('acm_donation_items', $data, array('id'=>$row->id));
	$_SESSION['alert'] = array(
		'type'=>'success',
		'message'=>_('Your item has been saved.')
	);
	header("Location: ".$appURL."/".$language_id."/settings");
	exit;
}
elseif(isset($_GET['delete_donation_item'])){
	if($demoMode){
		$_SESSION['alert'] = array(
			'type'=>'danger',
			'message'=>_('This is a demo mode and you cannot edit these settings.')
		);
		header("Location: ".$appURL."/".$language_id."/settings");
		exit;
	}
	$sql = 'SELECT * FROM acm_donation_items WHERE id = ?';
	$params = array($_GET['delete_donation_item']);
	$row = $db_game->row($sql, $params);
	if(!isset($row->id)){
		$_SESSION['alert'] = array(
			'type'=>'danger',
			'message'=>_('Something went wrong.')
		);
		header("Location: ".$appURL."/".$language_id."/settings");
		exit;
	}
	if($db_game->delete('acm_donation_items', array('id'=>$row->id))){
		$_SESSION['alert'] = array(
			'type'=>'success',
			'message'=>_('Your item has been removed.')
		);
	}
	header("Location: ".$appURL."/".$language_id."/settings");
	exit;
}

$js = "
<script>
	$('#email-form select[name=\"use_mailjet\"]').on('change', function(){
		if($(this).val() == '0'){
			$('#mailjet-info').addClass('d-none');
			$('#mailjet-info input').attr('required', false);
			$('#smtp-info').removeClass('d-none');
			$('#smtp-info input').attr('required', true);
			$('#smtp-info select').attr('required', true);
		}
		else {
			$('#smtp-info').addClass('d-none');
			$('#smtp-info input').attr('required', false);
			$('#smtp-info select').attr('required', false);
			$('#mailjet-info').removeClass('d-none');
			$('#mailjet-info input').attr('required', true);
		}
	})
	$('#general-form select[name=\"show_stats\"]').on('change', function(){
		if($(this).val() == '1'){
			$('#server-stats').removeClass('d-none');
		}
		else {
			$('#server-stats').addClass('d-none');
		}
	})
	$('#donation-form select[name=\"enable_donations\"]').on('change', function(){
		if($(this).val() == '1'){
			$('#donation-settings').removeClass('d-none');
		}
		else {
			$('#donation-settings').addClass('d-none');
		}
	})
	$('#donation-form select[name=\"enable_paypal\"]').on('change', function(){
		if($(this).val() == '0'){
			$('#paypal-settings').addClass('d-none').find('input').attr('required', false);
		}
		else {
			$('#paypal-settings').removeClass('d-none').find('input').attr('required', true);
		}
	})
	$('#donation-form select[name=\"enable_stripe\"]').on('change', function(){
		if($(this).val() == '1'){
			$('#stripe-settings').removeClass('d-none').find('input').attr('required', true);
		}
		else {
			$('#stripe-settings').addClass('d-none').find('input').attr('required', false);
		}
	})
	$('#new-item-form select[name=\"allow_multiple\"]').on('change', function(){
		if($(this).val() == '1'){
			$('#new-item-form .max-order-quantity').removeClass('d-none')
		}
		else {
			$('#new-item-form .max-order-quantity').addClass('d-none')
		}
	})
	$('#edit-item-form select[name=\"allow_multiple\"]').on('change', function(){
		if($(this).val() == '1'){
			$('#edit-item-form .max-order-quantity').removeClass('d-none')
		}
		else {
			$('#edit-item-form .max-order-quantity').addClass('d-none')
		}
	})
	$('.edit-item').on('click', function(e){
		e.preventDefault();
		let _id = $(this).data('id');
		$.post('".$appURL."/".$language_id."/settings', {show_donation_item: _id}, function(data){
			if(!data.success){
				alert('"._('Something went wrong.')."');
				return;
			}
			$('#edit-item-form')[0].reset();
			$.each(data.item, function(key,val) {
				if(val !== null){
					if($('#edit-item-form [name=\"'+key+'\"]').length){
						$('#edit-item-form [name=\"'+key+'\"]').val(val);
					}
				}
			});
			if(data.item.allow_multiple == 1){
				$('#edit-item-form .max-order-quantity').removeClass('d-none')
			}
			else {
				$('#edit-item-form .max-order-quantity').addClass('d-none')
			}
			$('#edit-item-form input[name=\"edit_donation_item\"]').val(_id);
			$('#edit-donation').modal('show');
		}).fail(function(){
			alert('"._('Something went wrong.')."');
		})
	})
	$('#delete-donation-item').on('click', function(e){
		e.preventDefault();
		let _id = $('#edit-item-form input[name=\"edit_donation_item\"]').val();
		if(!confirm('Are you sure? This action cannot be reverted.')){
			return false;
		}
		window.location.href = '".$appURL."/".$language_id."/settings?delete_donation_item='+_id;
	})
</script>
";

$page = array(
    'title'=>_('ACM Settings'),
	'js'=>$js
);

$sql = 'SELECT * FROM acm_donation_items';
$items = $db_game->fetch($sql);

if(isset($_SESSION['alert'])){
	$alert = $_SESSION['alert'];
	unset($_SESSION['alert']);
}

require_once $appHelpers . 'game-classes.php';